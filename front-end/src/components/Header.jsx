import { Link } from "react-router-dom";
import { IoIosLogIn } from "react-icons/io";
import logo from '../assets/logovelomobile.png';
import { CiHospital1, CiLogout } from "react-icons/ci";
import { PiPersonSimpleBike } from "react-icons/pi";
import { IoHomeOutline } from "react-icons/io5";
import { PiContactlessPaymentThin } from "react-icons/pi";


import { useState } from 'react';

const Header = ({ isLoggedIn, toggleMode, darkMode }) => {
  const [openCloseMenuBurger, setOpenCloseMenuBurger] = useState("inactive");
  let pathLogged = isLoggedIn ? '/logout' : '/login'
  function logInOutLink(login) {
    if (login) {
      return (<><CiLogout /><span>Déconnexion</span></>);
    } else return (<><IoIosLogIn /><span>Connexion</span></>);
  }
  function openCloseMenuBurgerClass() {
    const classAction = openCloseMenuBurger === "inactive" ? "active" : "inactive";
    console.log(`dans openCloseMenuBurgerClass`, classAction);
    setOpenCloseMenuBurger(classAction);
  }
  return (
    <header>
      <nav id="nav" className={openCloseMenuBurger}>
        <ul className='mt-4 '>
          <div>
            <li className=''
              onClick={openCloseMenuBurgerClass}>
              <Link to={`/`}><IoHomeOutline /><span>Accueil</span></Link>
            </li>
            <li className='products-link'
              onClick={openCloseMenuBurgerClass}>
              <Link to={`/products`}><PiPersonSimpleBike /><span>Produits</span></Link>
            </li>
            <li className='conctact'
              onClick={openCloseMenuBurgerClass}>
              <Link to={'/contact'}><PiContactlessPaymentThin />Contact</Link>
            </li>
          </div>
          <div>
            <li className='login-out-link'
              onClick={openCloseMenuBurgerClass}>
              <Link to={pathLogged}>{isLoggedIn ? logInOutLink(true) : logInOutLink(false)}</Link>
            </li>
            <li >
              <button
                onClick={toggleMode}
                className='btn btn-secondary'>{(darkMode === "light") ? "Mode sombre" : "Mode clair"}</button>
            </li>
          </div>
        </ul>
        <div id="icons"
          onClick={openCloseMenuBurgerClass}>
        </div>
      </nav>

      <div className="d-flex align-items-center" id="logo-baseline">
        <Link to={'/'}><img src={logo} alt="Logo vélomobile - retour accueil" /></Link>
        <h1>Vélomobile : l'alternative à la voiture</h1>
      </div>
    </header>
  );
}

export default Header;