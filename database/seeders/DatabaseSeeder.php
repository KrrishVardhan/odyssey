<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Team A: Leaf Village ---
        $leafLeader = User::create([
            'name' => 'Krrish',
            'email' => 'krrish@demo.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);

        $leafMembers = collect(['SUDO', 'hyt', 'maomao', 'Levi'])->map(fn($name) => User::create([
            'name' => $name,
            'email' => strtolower($name) . '@demo.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]));

        $leafVillage = Team::create([
            'owner_id' => $leafLeader->id,
            'name' => 'Leaf Village!',
            'slug' => 'leaf-village-' . Str::random(5),
            'description' => 'The Best Tech community!',
        ]);

        $leafVillage->members()->attach($leafLeader->id, ['role' => 'leader', 'joined_at' => now()]);
        $leafVillage->members()->attach($leafMembers->first()->id, ['role' => 'co_leader', 'joined_at' => now()]);
        $leafVillage->members()->attach($leafMembers->slice(1)->pluck('id'), ['role' => 'member', 'joined_at' => now()]);

        $leafProduct = Product::create([
            'team_id' => $leafVillage->id,
            'name' => 'Odyssey',
            'slug' => 'odyssey-' . Str::random(5),
            'description' => 'Ship better products together.',
            'about' => "Odyssey is a product feedback and shipping platform built around one simple idea: feedback becomes actionable work.",
            'is_public' => true,
        ]);

        // --- Team B: Ember Village ---
        $emberLeader = User::create([
            'name' => 'Itachi',
            'email' => 'itachi@demo.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);

        $emberMembers = collect(['Shisui', 'Obito', 'Konan', 'Nagato'])->map(fn($name) => User::create([
            'name' => $name,
            'email' => strtolower($name) . '@demo.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]));

        $emberVillage = Team::create([
            'owner_id' => $emberLeader->id,
            'name' => 'Ember Village',
            'slug' => 'ember-village-' . Str::random(5),
            'description' => 'AI-powered mission dispatch for shadow ops.',
        ]);

        $emberVillage->members()->attach($emberLeader->id, ['role' => 'leader', 'joined_at' => now()]);
        $emberVillage->members()->attach($emberMembers->first()->id, ['role' => 'co_leader', 'joined_at' => now()]);
        $emberVillage->members()->attach($emberMembers->slice(1)->pluck('id'), ['role' => 'member', 'joined_at' => now()]);

        $emberProduct = Product::create([
            'team_id' => $emberVillage->id,
            'name' => 'Shadow Dispatch',
            'slug' => 'shadow-dispatch-' . Str::random(5),
            'description' => 'AI-powered mission routing for field ops.',
            'about' => "Shadow Dispatch coordinates mission assignments in real time, so no operative is ever left without orders.",
            'is_public' => true,
        ]);

        // --- Tasks + assignments, mixed states, both teams ---
        $this->seedHackathonTasks($leafVillage, $leafLeader, $leafMembers);
        $this->seedHackathonTasks($emberVillage, $emberLeader, $emberMembers);

        // --- Submissions, tied to each team's product ---
        $this->seedSubmissions($leafProduct);
        $this->seedSubmissions($emberProduct);
    }

    private function seedHackathonTasks(Team $team, User $leader, $members): void
    {
        $tasks = [
            ['title' => 'Set up project repo + CI', 'priority' => 'high', 'status' => 'completed'],
            ['title' => 'Design system + Figma tokens', 'priority' => 'medium', 'status' => 'completed'],
            ['title' => 'Build auth flow', 'priority' => 'high', 'status' => 'in_progress'],
            ['title' => 'Wire up live demo API', 'priority' => 'high', 'status' => 'in_progress'],
            ['title' => 'Write pitch deck outline', 'priority' => 'medium', 'status' => 'pending'],
            ['title' => 'Record demo video', 'priority' => 'low', 'status' => 'pending'],
            ['title' => 'Fix mobile responsiveness bug', 'priority' => 'medium', 'status' => 'rejected'],
            ['title' => 'Deploy to staging', 'priority' => 'high', 'status' => null],
        ];

        foreach ($tasks as $t) {
            $task = $team->tasks()->create([
                'title' => $t['title'],
                'priority' => $t['priority'],
                'due_date' => now()->addDays(rand(1, 5)),
                'created_by' => $leader->id,
            ]);

            if ($t['status'] === null) {
                continue;
            }

            $assignee = $members->random();

            $data = [
                'user_id' => $assignee->id,
                'assigned_by' => $leader->id,
                'status' => $t['status'] === 'rejected' ? 'rejected' : ($t['status'] === 'pending' ? 'pending' : $t['status']),
            ];

            if (in_array($t['status'], ['in_progress', 'completed', 'rejected'])) {
                $data['responded_at'] = now()->subHours(rand(1, 48));
            }
            if ($t['status'] === 'rejected') {
                $data['rejection_reason'] = 'Already swamped with another deliverable, please reassign.';
            }
            if ($t['status'] === 'completed') {
                $data['completed_at'] = now()->subHours(rand(1, 24));
            }

            $task->assignments()->create($data);
        }
    }

    private function seedSubmissions(Product $product): void
    {
        $entries = [
            ['type' => 'bug', 'title' => 'Login button unresponsive on Safari', 'status' => 'submitted'],
            ['type' => 'feature', 'title' => 'Add dark mode to the dashboard', 'status' => 'submitted'],
            ['type' => 'feedback', 'title' => 'Really enjoying the new UI!', 'status' => 'resolved'],
            ['type' => 'improvement', 'title' => 'Make the search bar faster', 'status' => 'under_review'],
        ];

        foreach ($entries as $entry) {
            $product->submissions()->create([
                'team_id' => $product->team_id,
                'type' => $entry['type'],
                'status' => $entry['status'],
                'title' => $entry['title'],
                'description' => $entry['title'] . ' — reported during the demo hackathon.',
                'raw_input' => $entry['title'],
                'submitter_email' => 'anon' . rand(100, 999) . '@example.com',
            ]);
        }
    }
}
