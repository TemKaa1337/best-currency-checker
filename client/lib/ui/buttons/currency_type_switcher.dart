import 'package:flutter/material.dart';

class CurrencyTypeSwitcher extends StatefulWidget {
  final Function(String) notifyParent;

  const CurrencyTypeSwitcher({Key? key, required final Function(String) this.notifyParent}) : super(key: key);

  @override
  _CurrencyTypeSwitcherState createState() => _CurrencyTypeSwitcherState();
}

class _CurrencyTypeSwitcherState extends State<CurrencyTypeSwitcher> {
  final List<bool> _currencySelections = List.generate(2, (index) => index == 0 ? true : false);
  final List<String> _currencies = ['USD', 'EUR'];

  int _currencyEnabled = 0;

  void notifyParent() {
    widget.notifyParent(_currencies[_currencyEnabled]);
  }

  @override
  Widget build (BuildContext context) {
    return Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          const Padding(
            padding: EdgeInsets.all(10),
            child: Text('Choose currency:'),
          ),
          const Spacer(),
          Padding(
            padding: const EdgeInsets.all(5),
            child: Container(
              height: 40,
              child: ToggleButtons(
                children: _currencies.map((String element) => Text(element)).toList(),
                onPressed: (int index) {
                  setState(() {
                    int otherIndex = index == 0 ? index + 1 : index - 1;

                    if (_currencySelections[index] == false) {
                      _currencySelections[otherIndex] = !_currencySelections[otherIndex];
                      _currencyEnabled = index;
                      _currencySelections[index] = !_currencySelections[index];
                    }
                  });

                  notifyParent();
                },
                isSelected: _currencySelections,
                borderRadius: const BorderRadius.all(Radius.circular(10)),
                borderWidth: 1,
                selectedColor: Colors.black,
                fillColor: Colors.blue,
                color: Colors.black,
              )
            )
          ),
        ]
    );
  }
}