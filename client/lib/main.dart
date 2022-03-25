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
// 12. when pressing apply button transfer to 1 page (done)
// 13. beautify error messages (done)
// 14. add application icon (done)
// 15. add other cities rates (done)
// 16. change app name (done)
// 17. make russian application (done)
// 18. update readme (done)
// 19. fix buttons (done)
// 20. add google map (done)

// flutter build apk --release
// build/app/outputs/flutter-apk/app-release.apk
