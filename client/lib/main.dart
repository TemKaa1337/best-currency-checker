import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

void main() => runApp(const App());

class App extends StatelessWidget {
  const App({Key? key}) : super(key: key);

  @override
  Widget build (BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        primaryColor: Colors.blue
      ),
      home: Scaffold(
        appBar: AppBar(
          title: const Text('test')
        ),
        body: const CurrencyRates()
      )
    );
  }
}

class CurrencyRates extends StatefulWidget {
  const CurrencyRates({Key? key}) : super(key: key);

  @override
  _CurrencyRatesState createState() => _CurrencyRatesState();
}

class _CurrencyRatesState extends State<CurrencyRates> {
  Future<http.Response> getCurrencyRates() async {
    var response = await http.post(Uri.parse('https://best-currency-rates.temkaatrashprojects.tech/api/get/nearest/departments'));

    return response;
  }

  @override
  Widget build (BuildContext context) {
    return const Scaffold(

    );
  }
}

class CurrencyRate {

}
