import 'package:flutter/material.dart';

class RadiusSwitcher extends StatefulWidget {
  final Function(int) notifyParent;

  const RadiusSwitcher({Key? key, required final Function(int) this.notifyParent}) : super(key: key);

  @override
  _RadiusSwitcherState createState() => _RadiusSwitcherState();
}

class _RadiusSwitcherState extends State<RadiusSwitcher> {
  final List<bool> _radiusSelections = List.generate(5, (index) => index == 0 ? true : false);
  final List<int> _radiuses = [500, 1000, 2000, 5000, 10000];

  int _radiusEnabled = 0;

  void notifyParent() {
    widget.notifyParent(_radiuses[_radiusEnabled]);
  }

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        const Padding(
            padding: EdgeInsets.all(10),
            child: Text('Radius to search for?')
        ),
        Padding(
            padding: const EdgeInsets.all(10),
            child: ToggleButtons(
                children: _radiuses.map((int element) => Text((element / 1000).toString() + 'km')).toList(),
                onPressed: (int index) {
                  setState(() {
                    for (int i = 0; i < _radiusSelections.length; i ++) {
                      if (i == index) {
                        if (_radiusSelections[i] == false) {
                          _radiusSelections[i] = !_radiusSelections[i];
                          _radiusEnabled = i;
                        }
                      } else {
                        _radiusSelections[i] = false;
                      }
                    }

                    print(_radiusSelections);
                  });

                  notifyParent();
                },
                isSelected: _radiusSelections,
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