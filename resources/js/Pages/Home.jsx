import React from 'react';

export default function Home() {
    return (
        <div style={{ display: 'flex', height: '100vh', alignItems: 'center', justifyContent: 'center', backgroundColor: '#f1f5f9', fontFamily: 'sans-serif' }}>
            <div style={{ backgroundColor: 'white', padding: '40px', borderRadius: '8px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)', textAlign: 'center' }}>
                <h1 style={{ color: '#2563eb', margin: '0 0 10px 0' }}>Novelia Berhasil Dimuat! 🎉</h1>
                <p style={{ color: '#64748b', margin: 0 }}>Mulai dari awal terbukti sukses.</p>
            </div>
        </div>
    );
}