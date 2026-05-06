<?php
use App\Permission;
use App\Role;

// 1. Criar a permissão global
$permissionName = 'view-all-content';
$perm = Permission::where('name', $permissionName)->first();

if (!$perm) {
    $perm = Permission::create([
        'name' => $permissionName,
        'display_name' => 'Visualizar Todo o Conteúdo',
        'description' => 'Permite visualizar fotos e álbuns de todos os usuários, ignorando restrições de dono.'
    ]);
    echo "Permissão 'view-all-content' criada.\n";
}

// 2. Atribuir ao Superadministrator e Administrator
$roles = Role::whereIn('name', ['superadministrator', 'administrator'])->get();
foreach ($roles as $role) {
    if (!$role->hasPermission($permissionName)) {
        $role->attachPermission($perm);
        echo "Permissão atribuída ao cargo: {$role->name}\n";
    }
}

echo "Configuração de permissões concluída!\n";
