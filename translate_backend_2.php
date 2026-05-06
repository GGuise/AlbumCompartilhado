<?php

$dir = __DIR__ . '/resources/views/backend';

$translations = [
    '>users</li>' => '>Usuários</li>',
    '>All users</h3>' => '>Todos os Usuários</h3>',
    '<th>Verified</th>' => '<th>Verificado</th>',
    '<th>Role</th>' => '<th>Cargo</th>',
    '<th>Details</th>' => '<th>Detalhes</th>',
    '>verified<' => '>Verificado<',
    '>not verified<' => '>Não verificado<',
    '>No role set yet<' => '>Nenhum cargo definido<',
    '>User Settings</li>' => '>Configurações de Usuário</li>',
    '>User Details</h3>' => '>Detalhes do Usuário</h3>',
    '>Edit Settings</h3>' => '>Editar Configurações</h3>',
    '>Confirm Password</label>' => '>Confirmar Senha</label>',
    'value="Update"' => 'value="Atualizar"',
    'value="Create"' => 'value="Criar"',
    'value="Save"' => 'value="Salvar"',
    'value="Delete"' => 'value="Excluir"',
    '<th>Name</th>' => '<th>Nome</th>',
    '<th>Description</th>' => '<th>Descrição</th>',
    '>Edit</button>' => '>Editar</button>',
    '>Delete</button>' => '>Excluir</button>',
    'class="btn btn-success">Edit</a>' => 'class="btn btn-success">Editar</a>',
    'class="btn btn-danger">Delete</a>' => 'class="btn btn-danger">Excluir</a>',
    "class='btn btn-success'>Edit</a>" => "class='btn btn-success'>Editar</a>",
    '>Add User</h3>' => '>Adicionar Usuário</h3>',
    '>Assign Roles</label>' => '>Atribuir Cargos</label>',
    '>Display Name</label>' => '>Nome de Exibição</label>',
    'placeholder="Type Display Name"' => 'placeholder="Digite o nome de exibição"',
    '>Create Role</button>' => '>Criar Cargo</button>',
    '>Create Permission</button>' => '>Criar Permissão</button>',
    '>Key</label>' => '>Chave</label>',
    '>Value</label>' => '>Valor</label>',
    'placeholder="Type Key"' => 'placeholder="Digite a Chave"',
    'placeholder="Type Value"' => 'placeholder="Digite o Valor"',
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

