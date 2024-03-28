<?php
    function getUsers() {
        $users = [];
        $file = fopen('users.csv', 'r');
        while ($row = fgetcsv($file)) {
            $user = array_map('trim', $row);
            $users[] = [
                'id' => $user[0],
                'email' => $user[1],
                'password' => $user[2],
            ];
        }
        fclose($file);
        return $users;
    }

    function getUserData($email, $password) {
        $users = getUsers();
        $foundUser = null;
        
        foreach ($users as $user) {
            if ($user['email'] == $email && $user['password'] == $password) {
                $foundUser = $user;
                break; // Detiene la búsqueda una vez que se encuentra el usuario
            }
        }
        
        return $foundUser;
    }
    
    // Función para crear un nuevo usuario
    function createUser($email, $password) {
        $users = getUsers();
        $id = count($users) + 1;
        $user = [
            'id' => $id,
            'email' => $email,
            'password' => $password,
        ];
        $users[] = $user;
        saveUsers($users);
        return $user;
    }
    
    
    function saveUsers($users) {
        $file = fopen('users.csv', 'w');
        foreach ($users as $user) {
            fputcsv($file, $user);
        }
        fclose($file);
    }
?>