import 'package:flutter/material.dart';

class CurrencyOperationSwitcher extends StatefulWidget {
  final Function(String) notifyParent;

  const CurrencyOperationSwitcher({Key? key, required final Function(String) this.notifyParent}) : super(key: key);

  @override
  _CurrencyOperationSwitcherState createState() => _CurrencyOperationSwitcherState();
}

class _CurrencyOperationSwitcherState extends State<CurrencyOperationSwitcher> {
  final List<bool> _operationTypeSelections = List.generate(2, (index) => index == 0 ? true : false);
  final List<String> _operations = ['Buy', 'Sell'];

  int _operationEnabled = 0;

  void notifyParent() {
    widget.notifyParent(_operations[_operationEnabled]);
  }

  @override
  Widget build (BuildContext context) {
    return Row(
      children: [
        const Padding(
          padding: EdgeInsets.all(10),
          child: Text('What action to perform?'),
        ),
        Padding(
            padding: const EdgeInsets.all(10),
            child: ToggleButtons(
                children: _operations.map((String element) => Text(element)).toList(),
                onPressed: (int index) {
                  setState(() {
                    int otherIndex = index == 0 ? index + 1 : index - 1;

                    if (_operationTypeSelections[index] == false) {
                      _operationTypeSelections[otherIndex] = !_operationTypeSelections[otherIndex];
                      _operationEnabled = index;
                      _operationTypeSelections[index] = !_operationTypeSelections[index];
                    }
                  });

                  notifyParent();
                },
                isSelected: _operationTypeSelections,
                borderRadius: BorderRadius.circular(10),
                borderWidth: 2,
                borderColor: Colors.white,
                selectedColor: Colors.black
            )
        )
      ],
    );
  }
}