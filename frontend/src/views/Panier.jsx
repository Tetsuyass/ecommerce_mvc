import { useEffect, useState } from "react";
import "../styles/Panier.css";
import Nav from "../components/Nav.jsx";
import Footer from "../components/Footer.jsx";

const API_URL = "http://localhost:8080"; // adapte si besoin

export default function Panier() {
    const [cart, setCart] = useState(null);
    const [loading, setLoading] = useState(true);

    /* =========================
       FETCH PANIER
    ========================= */
    const fetchCart = async () => {
        try {
            const res = await fetch(`${API_URL}/cart`, {
                credentials: "include"
            });
            const data = await res.json();
            setCart(data.data?.items ?? []);
        } catch (error) {
            console.error("Erreur chargement panier", error);
            setCart([]);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchCart();
    }, []);

    /* =========================
       SUPPRIMER UN ITEM
    ========================= */
    const removeItem = async (productId) => {
        await fetch(`${API_URL}/cart/remove`, {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id_produit: productId })
        });

        fetchCart();
    };

    /* =========================
       MODIFIER QUANTITÉ
    ========================= */
    const updateQuantity = async (productId, quantity) => {
        await fetch(`${API_URL}/cart/update`, {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: Number(quantity)
            })
        });

        fetchCart();
    };

    /* =========================
       TOTAL
    ========================= */
    const total = cart?.reduce(
        (sum, item) => sum + item.produit.prix * item.quantite,
        0
    );

    /* =========================
       VALIDER COMMANDE
    ========================= */
    const validateOrder = async () => {
        await fetch(`${API_URL}/orders`, {
            method: "POST",
            credentials: "include"
        });

        fetchCart();
        alert("Commande validée !");
    };

    /* =========================
       RENDER
    ========================= */
    if (loading) return <p>Chargement...</p>;

    if (!cart || cart.length === 0) {
        return (
            <div className="panier-wrapper">
                <Nav />
                <h2>Panier</h2>
                <p>Votre panier est vide.</p>
                <Footer />
            </div>
        );
    }

    return (
        <div className="panier-wrapper">
            <Nav />

            <h2>Panier</h2>

            <table className="panier-item-list">
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {cart.map((item) => (
                    <tr key={item.produit.id_produit}>
                        <td>{item.produit.nom_produit}</td>
                        <td>{item.produit.prix} €</td>
                        <td>
                            <input
                                type="number"
                                min="1"
                                value={item.quantite}
                                onChange={(e) =>
                                    updateQuantity(item.produit.id, e.target.value)
                                }
                            />
                        </td>
                        <td>
                            <button onClick={() => removeItem(item.produit.id_produit)}>
                                Supprimer
                            </button>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            <p className="panier-total-price">
                Total : <strong>{total} €</strong>
            </p>

            <button onClick={validateOrder}>
                Valider la commande
            </button>

            <Footer/>
        </div>
    );
}
