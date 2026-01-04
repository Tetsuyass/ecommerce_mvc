import '../../styles/DetailProduit.css'
import Nav from "../../components/Nav.jsx";
import Footer from "../../components/Footer.jsx";
import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";

export default function DetailProduit() {
    const { id } = useParams();

    const [product, setProduct] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        async function fetchProduct() {
            try {
                const response = await fetch(`http://localhost:8080/products/${id}`, {
                    credentials: 'include', // cohérent avec ton CORS
                });

                if (!response.ok) {
                    throw new Error('Produit introuvable');
                }

                const data = await response.json();

                setProduct(data.data);
                console.log("Réponse brute :", data);
                console.log("Produit réel :", data.data);

            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        }

        fetchProduct();
    }, [id]);

    if (loading) {
        return <p>Chargement du produit...</p>;
    }

    if (error) {
        return <p>Erreur : {error}</p>;
    }

    if (!product) {
        return <p>Aucun produit trouvé.</p>;
    }

    async function addToCart() {
        try {
            const response = await fetch("http://localhost:8080/cart/add", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_produit: id,
                    quantite: 1
                })
            });

            const data = await response.json();

            if (!response.ok || data.success === false) {
                throw new Error(data.message || "Impossible d'ajouter au panier");
            }

            // Pas d'alerte → on peut afficher un message visuel plus propre
            console.log("Produit ajouté au panier !");
        } catch (err) {
            console.error("Erreur :", err.message);
        }
    }


    return (
        <div className='product-detail-page-wrapper'>
            <div className='navbar-wrapper'>
                <Nav />
            </div>

            <div className='product-detail-wrapper'>
                <p>Produit : {product.nom_produit}</p>

                <div className='product-image-wrapper'>
                    <img
                        className='product-image'
                        src={product.image_produit}
                        alt={product.description_produit}
                    />
                </div>

                <div className='product-prix-wrapper'>
                    <p className='product-prix'>{product.prix} €</p>
                </div>

                <div className='product-description-wrapper'>
                    <p className='product-description'>
                        {product.description_produit}
                    </p>
                </div>

                <div className='product-add-cart-wrapper'>
                    <button
                        className='product-add-cart-btn'
                        onClick={addToCart}
                    >
                        Ajouter au panier
                    </button>
                </div>

            </div>

            <div className='footer-wrapper'>
                <Footer/>
            </div>
        </div>
    );
}
