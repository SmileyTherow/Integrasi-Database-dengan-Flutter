import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class RegisterPage extends StatefulWidget {
  const RegisterPage({super.key});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  TextEditingController username = TextEditingController();
  TextEditingController email = TextEditingController();
  TextEditingController password = TextEditingController();

  Future register() async {
    var url = Uri.parse(
      "http://localhost/flutter_api/auth/register.php",
    );

    var response = await http.post(
      url,
      body: {
        "username": username.text,
        "email": email.text,
        "password": password.text,
      },
    );

    var data = json.decode(response.body);

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(data["message"]),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Register"),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            TextField(
              controller: username,
              decoration: const InputDecoration(
                labelText: "Username",
              ),
            ),
            TextField(
              controller: email,
              decoration: const InputDecoration(
                labelText: "Email",
              ),
            ),
            TextField(
              controller: password,
              obscureText: true,
              decoration: const InputDecoration(
                labelText: "Password",
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                register();
              },
              child: const Text("Register"),
            ),
          ],
        ),
      ),
    );
  }
}