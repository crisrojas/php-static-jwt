import Foundation

enum Constants {
    static let localURL = "http://localhost:9000"
}

extension Data {
    var asString: String {
        String(decoding: self, as: UTF8.self)
    }
}

struct AuthCommand: Codable {
    let email: String
    let password: String
}

struct AuthToken: Codable {
    let accessToken: String
    let refreshToken: String
}

func get() async throws -> Data {
    let url = URL(string: Constants.localURL)!
    var request = URLRequest(url: url)
    request.httpMethod = "GET"
    let (data, _) = try await URLSession.shared.data(for: request)
    return data
}

func getRecipes(accessToken: String) async throws -> Data {
    let url = URL(string: Constants.localURL + "/recipes")!
    var request = URLRequest(url: url)
    request.httpMethod = "GET"
    request.setValue("Bearer \(accessToken)", forHTTPHeaderField: "Authorization")
    
    let (data, _) = try await URLSession.shared.data(for: request)
    return data
}


let jsonEncoder = JSONEncoder()
let jsonDecoder = JSONDecoder()

func login(email: String, password: String) async throws -> Data {
    let authCommand = AuthCommand(email: email, password: password)
    let body = try jsonEncoder.encode(authCommand)
    let url = URL(string: "\(Constants.localURL)/login")!
    var request = URLRequest(url: url)
    request.httpMethod = "post"
    request.httpBody = body
    let (data, _) = try await URLSession.shared.data(for: request)
    return data
}



do {
    let raw_authToken = try await login(email: "cristian@rojas.fr", password: "1234")
    let authToken = try jsonDecoder.decode(AuthToken.self, from: raw_authToken)
    
    let recipes = try await getRecipes(accessToken: authToken.accessToken)
    print(recipes.asString)
} catch {
    print(error.localizedDescription)
}