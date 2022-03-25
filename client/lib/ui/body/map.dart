import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/gestures.dart';

class GoogleMapTab extends StatefulWidget {
  final LatLng userPosition;
  final List<Map<String, dynamic>> departmentsInfo;

  const GoogleMapTab({
    Key? key,
    required LatLng this.userPosition,
    required List<Map<String, dynamic>> this.departmentsInfo
  }) : super(key: key);

  @override
  _GoogleMapTabState createState() => _GoogleMapTabState();
}

class _GoogleMapTabState extends State<GoogleMapTab>{
  late GoogleMapController _controller;

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    Set<Marker> markers = {};
    CameraPosition position = CameraPosition(
      target: widget.userPosition,
      zoom: 13
    );

    Marker userPosition = Marker(
      markerId: const MarkerId('user_position'),
      infoWindow: const InfoWindow(
        title: 'Это вы'
      ),
      icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueRed),
      position: widget.userPosition
    );

    markers.add(userPosition);

    for(Map<String, dynamic> departmentInfo in widget.departmentsInfo) {
      List coordinates = departmentInfo['coordinates'].map((String element) => double.parse(element.trim())).toList();
      LatLng departmentPosition = LatLng(coordinates[0], coordinates[1]);

      markers.add(Marker(
          markerId: const MarkerId('user_position'),
          infoWindow: InfoWindow(
            title: departmentInfo['name'].toString() + " ${departmentInfo['sign']}${departmentInfo['currency_rate']}",
            snippet: " (в ${departmentInfo['distance'].toString()} метрах от вас)"
          ),
          icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueGreen),
          position: departmentPosition
      ));
    }

    return Scaffold(
      body: Column(
        children: [
          Expanded(
            child: GoogleMap(
              initialCameraPosition: position,
              onMapCreated: (controller) {
                _controller = controller;
              },
              markers: markers,
              zoomControlsEnabled: false,
              zoomGesturesEnabled: true,
              scrollGesturesEnabled: true,
              compassEnabled: true,
              rotateGesturesEnabled: true,
              mapToolbarEnabled: true,
              tiltGesturesEnabled: true,
              gestureRecognizers: < Factory < OneSequenceGestureRecognizer >> [
                new Factory < OneSequenceGestureRecognizer > (
                      () => new EagerGestureRecognizer(),
                ),
              ].toSet()
            )
          )
        ],
      )
    );
  }

}