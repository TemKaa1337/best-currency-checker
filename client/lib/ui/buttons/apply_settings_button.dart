import 'package:flutter/material.dart';

class ApplySettingsButton extends StatefulWidget {
  final Function() notifyParent;

  const ApplySettingsButton({Key? key, required Function() this.notifyParent}) : super(key: key);

  @override
  _ApplySettingsButtonState createState() => _ApplySettingsButtonState();
}

class _ApplySettingsButtonState extends State<ApplySettingsButton> {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: 100,
      child: Align(
        child: SizedBox(
          width: 100,
          child: ElevatedButton.icon(
            onPressed: () {
              widget.notifyParent();
            },
            label: const Text('Apply', style: TextStyle(color: Colors.black)),
            icon: const Icon(Icons.check, color: Colors.black,)
          ),
        )
      )
    );
  }
}