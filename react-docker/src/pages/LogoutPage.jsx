import { useOutletContext } from "react-router-dom";
const LogoutPage = () => {
  const [isLoggedIn, setIsLoggedIn] = useOutletContext();
  setIsLoggedIn(false);
  return (
    <section>
      <h2>Vous êtes bien déconnecté</h2>
    </section>


  );
}

export default LogoutPage;