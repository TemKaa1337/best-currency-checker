class Department {
  final int id;
  final String name, address, website;

  Department(this.id, this.name, this.address, this.website);

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'address': address,
    'website': website
  };
}