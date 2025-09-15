.container {
    background: #fff;
    width: 900px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.form-content {
    display: flex;
    height: 100%;
    min-height: 500px;   /* Biar tinggi stabil */
}

.image-side {
    width: 50%;
    height: auto;
}

.image-side img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.text-side {
    width: 50%;
    padding: 50px;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
