import '../styles/Nav.css'
import logo from '../assets/home.svg'
import { useContext } from "react";
import { AuthContext } from "../context/AuthContext";
import { Link, useNavigate } from "react-router-dom";
import useAuth from "../hooks/useAuth";

export default function Nav() {
    const { user, setUser, loading } = useContext(AuthContext);
    const navigate = useNavigate();

    async function logout() {
        await fetch("http://localhost:8080/auth/logout", {
            method: "POST",
            credentials: "include"
        });

        setUser(null);
        navigate("/login");
    }

    return (
        <div className="navbar">
            <div className='navbar-title-wrapper'>
                <h2 className='navbar-title'>Balmung Store</h2>
            </div>

            {/* Pendant le chargement de /auth/me */}
            {loading && <span>Chargement...</span>}

            {/* Si l'utilisateur est connecté */}
            {!loading && user && (
                <>
                    <span className="navbar-user">
                        Bonjour {user.prenom || user.email}
                    </span>

                    <Link to="/user-space">Espace Client</Link>

                    <Link to="/panier">Panier</Link>

                    <button className="navbar-logout" onClick={logout}>
                        Déconnexion
                    </button>
                </>
            )}

            {/* Si l'utilisateur n'est pas connecté */}
            {!loading && !user && (
                <Link to="/login">Connexion</Link>
            )}

            <div className='logo-wrapper'>
                <Link to='/' className='logo'>
                    <img src={logo} alt='logo'/>
                </Link>
            </div>
        </div>
    );
}
