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
  final List<String> _operationLabels = ['Покупка', 'Продажа'];

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
          child: Text('Тип операции:'),
        ),
        const Spacer(),
        Padding(
          padding: const EdgeInsets.all(10),
          child: Container(
            height: 40,
            child: ToggleButtons(
              children: _operationLabels.map((String element) => Padding(padding: EdgeInsets.all(5), child: Text(element),)).toList(),
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
              borderRadius: const BorderRadius.all(Radius.circular(10)),
              borderWidth: 1,
              selectedColor: Colors.black,
              fillColor: Colors.blue,
              color: Colors.black,
            )
          )
        )
      ],
    );
  }
}