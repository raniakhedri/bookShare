# 📋 GUIDE: Charger l'extension BookShare dans Chrome

## ⚠️ IMPORTANT: Bon dossier à sélectionner

Vous devez sélectionner ce dossier:
```
C:\Users\rannn\Desktop\bookShare\browser-extension
```

**❌ PAS**: `C:\Users\rannn\Desktop\bookShare` (le dossier parent)

---

## 📝 Instructions étape par étape

### Étape 1: Ouvrir Chrome Extensions
1. Ouvrez Google Chrome
2. Tapez dans la barre d'adresse: `chrome://extensions/`
3. Appuyez sur Entrée

### Étape 2: Activer le Mode Développeur
En haut à droite de la page, activez le toggle:
```
Mode développeur: [ON]
```

### Étape 3: Charger l'extension
1. Cliquez sur le bouton **"Charger l'extension non empaquetée"** (ou "Load unpacked")
2. Dans la fenêtre qui s'ouvre, naviguez vers:
   ```
   C:\Users\rannn\Desktop\bookShare\browser-extension
   ```
3. ✅ Sélectionnez ce dossier `browser-extension` 
4. Cliquez sur **"Sélectionner le dossier"**

### Étape 4: Vérifier l'installation
Vous devriez voir:
```
✅ BookShare Marketplace Checker
   Version 1.0.0
   ID: [un ID généré automatiquement]
```

---

## 🔍 Vérification du dossier

Le dossier `browser-extension` doit contenir:
- ✅ `manifest.json`
- ✅ `content.js`
- ✅ `content.css`
- ✅ `background.js`
- ✅ `popup.html`
- ✅ `popup.js`
- ✅ `options.html`
- ✅ `options.js`
- ✅ `icons/icon16.png`
- ✅ `icons/icon48.png`
- ✅ `icons/icon128.png`

---

## ❌ Si vous voyez une erreur

### Erreur: "Manifest file is missing or unreadable"
**Cause**: Vous avez sélectionné le mauvais dossier

**Solution**: 
- Réessayez en sélectionnant `browser-extension` (pas `bookShare`)
- Le dossier doit contenir directement `manifest.json`

### Erreur: "Could not load icon"  
**Cause**: Les fichiers PNG d'icônes sont manquants

**Solution**: 
- Vérifiez que les 3 fichiers PNG existent dans `icons/`
- Si non, ouvrez `icon-generator.html` et téléchargez-les

---

## ✅ Extension chargée avec succès!

Une fois chargée, vous verrez l'icône BookShare dans votre barre d'outils Chrome.

### Test rapide:
1. Allez sur https://www.amazon.com
2. Cherchez un livre
3. Ouvrez une page de livre
4. Vous devriez voir une notification BookShare! 🎉

---

## 🎯 Prochaines étapes

1. ✅ L'extension est installée
2. Démarrez le serveur Laravel:
   ```bash
   cd C:\Users\rannn\Desktop\bookShare
   php artisan serve
   ```
3. Ajoutez des livres au marketplace
4. Testez sur Amazon!

---

**Besoin d'aide?** Consultez `QUICK_START.md` pour plus de détails.
