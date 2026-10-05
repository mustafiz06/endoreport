<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Tests\TestCase;

class RbacAuthorizationTest extends TestCase
{
    public function test_super_admin_has_access_to_all_modules(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();

        $routes = [
            '/dashboard',
            '/patients',
            '/doctors',
            '/referring-doctors',
            '/procedures',
            '/instruments',
            '/examinations',
            '/patient-history',
            '/reports',
            '/report-templates',
            '/users',
            '/roles',
            '/settings',
            '/audit',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_audit_logs_record_actions_correctly(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();

        // Perform an audit-logged action
        $response = $this->actingAs($admin)->post('/patients', [
            'name' => 'Audit Test Patient',
            'gender' => 'Female',
            'phone' => '+1 (555) 332-9900',
            'status' => 'Active',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Created Patient',
            'module' => 'Patients',
        ]);
    }
}
