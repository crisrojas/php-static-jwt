<?php    
    function generateJWT($user, $time) {
        // Encabezado
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $header = base64_encode($header);
        
        // Carga útil
        $payload = json_encode([
            'sub' => $user['id'], // Identificador del usuario
            'email' => $user['email'], // Correo electrónico del usuario
            'iat' => time(), // Tiempo de emisión
            'exp' => time() + ($time), // Tiempo de expiración (1 hora)
        ]);
        $payload = base64_encode($payload);
        
        // Firma
        $signature = hash_hmac('sha256', "$header.$payload", 'your_secret_key');
        
        // Token JWT completo
        $accessToken = "$header.$payload.$signature";
        return $accessToken;
    }

    function verifyJWT($jwt) {
        list($header, $payload, $signature) = explode('.', $jwt);
        
        // Decodificar y verificar el encabezado y la carga útil
        $decodedHeader = json_decode(base64_decode($header), true);
        $decodedPayload = json_decode(base64_decode($payload), true);
        
        // Verificar la firma
        $expectedSignature = hash_hmac('sha256', "$header.$payload", 'your_secret_key');
        if ($expectedSignature !== $signature) {
            return false; // La firma no coincide, el token es inválido
        }
        
        // Verificar si el token ha expirado
        if (time() > $decodedPayload['exp']) {
            return false; // El token ha expirado
        }
        
        return $decodedPayload; // El token es válido, devolver la carga útil decodificada
    }
    
    function test() {
        // replace with token given after auth
        $decoded = verifyJWT('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJjcmlzdGlhbkByb2phcy5mciIsImlhdCI6MTcxMTY0MzcyOSwiZXhwIjoxNzExNjQ3MzI5fQ==.911ea559a4ddfd9bd01e75827792df15355162f81e1ed2a6b25e84db36b744d2');
        echo json_encode($decoded);
    }
?>