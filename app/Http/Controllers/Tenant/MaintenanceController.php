<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Enums\NotificationType;
use App\Models\MaintenanceRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $requests = MaintenanceRequest::where('tenant_id', $tenant->id)
            ->with('unit.property')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tenant.maintenance.index', compact('requests'));
    }

    public function create()
    {
        return view('tenant.maintenance.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $tenant = $request->user()->tenant;
        $activeLease = $tenant->activeLease;

        if (!$activeLease) {
            return back()->with('error', 'No active lease found');
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach (($request->file('photos') ?? []) as $photo) {
                if (! $photo instanceof UploadedFile || ! $photo->isValid()) {
                    continue;
                }

                $storedPhoto = $this->storePhotoFile($photo);
                if (! empty($storedPhoto)) {
                    $photos[] = $storedPhoto;
                }
            }
        }

        $maintenanceRequest = MaintenanceRequest::create([
            'unit_id' => $activeLease->unit_id,
            'tenant_id' => $tenant->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'photos' => $photos,
        ]);

        // Notify the property's agent about the new maintenance request
        $activeLease->load('unit.property.agent');
        $agent = $activeLease->unit->property->agent;
        if ($agent) {
            app(NotificationService::class)->notify(
                user: $agent,
                type: NotificationType::MAINTENANCE_UPDATE,
                subject: 'New Maintenance Request',
                message: 'New maintenance request: ' . $validated['title'],
                sendEmail: true,
            );
        }

        return redirect()->route('tenant.maintenance.index')
            ->with('success', 'Maintenance request submitted');
    }

    public function show(Request $request, string $org, MaintenanceRequest $maintenance)
    {
        $tenant = $request->user()->tenant;
        if ($maintenance->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this maintenance request.');
        }

        $maintenance->load(['unit.property', 'notes.user']);
        return view('tenant.maintenance.show', compact('maintenance'));
    }

    private function storePhotoFile(UploadedFile $photo): ?string
    {
        $sourcePath = $photo->getPathname();

        if (empty($sourcePath) || ! is_file($sourcePath) || ! is_readable($sourcePath)) {
            return null;
        }

        $targetPath = 'maintenance/' . $photo->hashName();
        $stream = fopen($sourcePath, 'r');

        if (! is_resource($stream)) {
            return null;
        }

        try {
            $stored = Storage::disk('public')->put($targetPath, $stream);
        } catch (\Throwable $e) {
            report($e);
            return null;
        } finally {
            fclose($stream);
        }

        return $stored ? $targetPath : null;
    }
}
