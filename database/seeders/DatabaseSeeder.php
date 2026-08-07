<?php

namespace Database\Seeders;

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
            'name' => 'Naruto',
            'email' => 'naruto@demo.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);

        $leafMembers = collect(['Sakura', 'Sasuke', 'Kakashi', 'Hinata'])->map(fn($name) => User::create([
            'name' => $name,
            'email' => strtolower($name) . '@demo.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]));

        $leafVillage = Team::create([
            'owner_id' => $leafLeader->id,
            'name' => 'Leaf Village',
            'slug' => 'leaf-village-' . Str::random(5),
            'description' => 'Building the future of decentralized shinobi comms.',
        ]);

        $leafVillage->members()->attach($leafLeader->id, ['role' => 'leader', 'joined_at' => now()]);
        $leafVillage->members()->attach($leafMembers->first()->id, ['role' => 'co_leader', 'joined_at' => now()]);
        $leafVillage->members()->attach($leafMembers->slice(1)->pluck('id'), ['role' => 'member', 'joined_at' => now()]);

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

        // --- Tasks + assignments, mixed states, both teams ---
        $this->seedHackathonTasks($leafVillage, $leafLeader, $leafMembers);
        $this->seedHackathonTasks($emberVillage, $emberLeader, $emberMembers);
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
            ['title' => 'Deploy to staging', 'priority' => 'high', 'status' => null], // unassigned, sits in To Do
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
}
