import 'package:flutter/material.dart';
import 'package:client/ui/home.dart';

void main() => runApp(const App());

class App extends StatelessWidget {
  const App({Key? key}) : super(key: key);

  @override
  Widget build (BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        primaryColor: Colors.blue
      ),
      home: const Home(),
      debugShowCheckedModeBanner: false
    );
  }
}

// TODO List
// 1. make api view  output (done)
// 2. add currency detector
// 3. add buy/sell detector
// 4. depending on detectors sort departments
// 5. add gps checker
// 6. add radius button
// 7. make api request with radius and gps
// 8. add error message show
// 9. add refresh button
