import 'package:flutter/material.dart';

class CurrencyTypeSwitcher extends StatefulWidget {
  const CurrencyTypeSwitcher({Key? key}) : super(key: key);

  @override
  _CurrencyTypeSwitcherState createState() => _CurrencyTypeSwitcherState();
}

class _CurrencyTypeSwitcherState extends State<CurrencyTypeSwitcher> {
  final List<bool> _selections = List.generate(2, (index) => index == 0 ? true : false);

  @override
  Widget build (BuildContext context) {
    return ToggleButtons(
        children: const [
          Text('USD'),
          Text('EUR')
        ],
        onPressed: (int index) {
          setState(() {
            int otherIndex = index == 0 ? index + 1 : index - 1;

            _selections[otherIndex] = !_selections[otherIndex];
            _selections[index] = !_selections[index];
          });
        },
        isSelected: _selections,
        borderRadius: BorderRadius.circular(25),
        borderWidth: 2,
        borderColor: Colors.white,
        selectedColor: Colors.black
    );
  }
}