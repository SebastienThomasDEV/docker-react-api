import { MapContainer, TileLayer, Marker, Popup, useMap } from 'react-leaflet';
import L from 'leaflet';
const ContactPage = () => {
  const position = [43.57595704616881, 3.946897798793441];
  const defaultIcon = L.icon({
    iconUrl: '/icon.png', // Replace with the actual path to your icon
    shadowUrl: '/shadow.png', // Replace with the actual path to your shadow image
    iconSize: [25, 41], // Size of the icon
    shadowSize: [41, 41], // Size of the shadow
    iconAnchor: [12, 41], // Point of the icon which will correspond to marker's location
    shadowAnchor: [14, 41], // The same for the shadow
    popupAnchor: [1, -34], // Point from which the popup should open relative to the iconAnchor
  });
  return (
    <section>
      <h2>Contact</h2>
      <div id="map-wrapper">
        <MapContainer style={{ height: '500px', width: '600px' }} center={position} zoom={15} scrollWheelZoom={true}>
          <TileLayer
            attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          <Marker position={position} icon={defaultIcon}>
            <Popup>
              Notre usine de construction de velosMobiles
            </Popup>
          </Marker>
        </MapContainer>,
      </div>

    </section>

  );
}

export default ContactPage;