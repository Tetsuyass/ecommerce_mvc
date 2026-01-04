import { useEffect, useState } from "react";
import '../../styles/EspaceClient.css';
import Nav from "../../components/Nav.jsx";
import Footer from "../../components/Footer.jsx";

const API_URL = "http://localhost:8080";

export default function EspaceClient() {
    const [history, setHistory] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // ============================
    // FETCH HISTORIQUE COMMANDES
    // ============================
    const fetchOrderHistory = async () => {
        try {
            const res = await fetch(`${API_URL}/orders`, {
                credentials: "include"
            });

            if (res.status === 401) {
                setError("Vous devez être connecté pour accéder à l'espace client.");
                setHistory([]);
                return;
            }

            const data = await res.json();

            console.log("Réponse brute :", res);
            console.log("JSON :", data);


            if (data.success) {
                if (Array.isArray(data.data)) {
                    setHistory(data.data);
                } else {
                    setHistory([]);
                }
            } else {
                setHistory([]);
            }
            if (data.success) {
                if (Array.isArray(data.data)) {
                    setHistory(data.data);
                } else {
                    setHistory([]);
                }
            } else {
                setHistory([]);
            }

        } catch (err) {
            console.error("Erreur fetch commandes :", err);
            setError("Erreur lors du chargement des commandes.");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchOrderHistory();
    }, []);

    // ============================
    // RENDER
    // ============================
    if (loading) {
        return <p>Chargement de vos commandes...</p>;
    }

    if (error) {
        return (
            <div className="user-space-wrapper">
                <Nav />
                <h2 className="user-space-title">Espace Client</h2>
                <p style={{ color: "red", textAlign: "center" }}>{error}</p>
                <Footer />
            </div>
        );
    }

    return (
        <div className='user-space-wrapper'>
            <div className='user-space-title-wrapper'>
                <h2 className='user-space-title'>Espace Client</h2>
            </div>

            <div className='navbar-wrapper'>
                <Nav />
            </div>

            <div className='history-items-list-container'>
                <div className='history-items-list-container-title-wrapper'>
                    <h3 className='history-items-list-container-title'>Historique des commandes</h3>
                </div>

                <table className='history-items-list'>
                    <thead>
                    <tr>
                        <th>Numéro de commande</th>
                        <th>Statut</th>
                        <th>Total (€)</th>
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th>Code postal</th>
                    </tr>
                    </thead>

                    <tbody>
                    {history.map(commande => (
                        <tr key={commande.id}>
                            <td>{commande.id}</td>
                            <td>{commande.statut}</td>
                            <td>{commande.total}</td>
                            <td>{commande.adresse_livraison}</td>
                            <td>{commande.ville_livraison}</td>
                            <td>{commande.code_postal}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>

            <div className='footer-wrapper'>
                <Footer />
            </div>
        </div>
    );
}
