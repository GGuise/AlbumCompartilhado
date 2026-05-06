<?php

$dir = __DIR__ . '/resources/views/backend';

$translations = [
    '>Create<' => '>Criar<',
    '>Edit<' => '>Editar<',
    
    '>Details user</h3>' => '>Detalhes do Usuário</h3>',
    '>Edit user</h3>' => '>Editar Usuário</h3>',
    '>Create User</h3>' => '>Criar Usuário</h3>',
    '>Create user</h3>' => '>Criar Usuário</h3>',

    '>Details team</h3>' => '>Detalhes da Equipe</h3>',
    '>Edit team</h3>' => '>Editar Equipe</h3>',
    '>Create Team</h3>' => '>Criar Equipe</h3>',
    '>Create team</h3>' => '>Criar Equipe</h3>',

    '>role Details</h3>' => '>Detalhes do Cargo</h3>',
    '>Edit role</h2>' => '>Editar Cargo</h2>',
    '>Create role</h3>' => '>Criar Cargo</h3>',

    '>All settings</h3>' => '>Todas as Configurações</h3>',
    '>Edit setting</h2>' => '>Editar Configuração</h2>',
    '>Create setting</h3>' => '>Criar Configuração</h3>',

    '>Service Details</h2>' => '>Detalhes do Serviço</h2>',
    '>Edit service</h3>' => '>Editar Serviço</h3>',
    '>Create Service</h3>' => '>Criar Serviço</h3>',

    '>Album Details</h3>' => '>Detalhes do Álbum</h3>',
    '>Edit Album</h2>' => '>Editar Álbum</h2>',
    '>Create album</h3>' => '>Criar Álbum</h3>',
    
    '>permission Details</h3>' => '>Detalhes da Permissão</h3>',
    '>Edit permission</h2>' => '>Editar Permissão</h2>',
    '>Create permission</h3>' => '>Criar Permissão</h3>',
    
    '>Create Contact Info</h3>' => '>Criar Informações de Contato</h3>',
    '>Edit Contcat Info</h3>' => '>Editar Informações de Contato</h3>',
    '>All infos</h3>' => '>Todas as Informações</h3>',
    '>Details Contact Info</h3>' => '>Detalhes das Informações de Contato</h3>',
    
    '>Edit photo</h2>' => '>Editar Foto</h2>',
];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $original = $content;
        
        foreach ($translations as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        
        if ($content !== $original) {
            file_put_contents($file->getRealPath(), $content);
            $count++;
        }
    }
}

echo "Translated $count files in backend.\n";

