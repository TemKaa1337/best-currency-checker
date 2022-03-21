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
// 2. add currency detector (done)
// 3. add buy/sell detector (done)
// 4. depending on detectors sort departments (done)
// 5. add gps checker (done)
// 6. add radius button (done)
// 7. make api request with radius and gps (done)
// 8. add error message show (done)
// 9. add refresh button (done)
// 10. fix pixel overflow when 15 elements (done)
// 11. make currency rate icon (done)
// 12. when pressing apply button transfer to 1 page
// 13. beautify error messages
// 14. add application icon (done)
// 15. add application version
// 16. add other cities rates
// 17. change app name (done)
// 18. make russian application
// 19. change app icon

// flutter build apk --release
// build/app/outputs/flutter-apk/app-release.apk
