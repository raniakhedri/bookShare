# 🚨 FIX: Extension Loading Error

## Problem
```
Erreur: Le fichier manifeste est manquant ou illisible
```

## Cause
Les fichiers d'icônes PNG n'existent pas encore.

---

## ✅ Solution Rapide (2 minutes)

### Étape 1: Ouvrir le générateur d'icônes
Le fichier `icon-generator.html` devrait déjà être ouvert dans votre navigateur.

**Si ce n'est pas le cas**, double-cliquez sur:
```
C:\Users\rannn\Desktop\bookShare\browser-extension\icons\icon-generator.html
```

### Étape 2: Télécharger les 3 icônes
Dans la page web, vous verrez 3 boutons:
- ☐ **Download 128x128** ← Cliquez ici
- ☐ **Download 48x48** ← Cliquez ici
- ☐ **Download 16x16** ← Cliquez ici

### Étape 3: Sauvegarder les fichiers
Sauvegardez les 3 fichiers PNG téléchargés dans:
```
C:\Users\rannn\Desktop\bookShare\browser-extension\icons\
```

Les fichiers doivent s'appeler:
- `icon128.png`
- `icon48.png`
- `icon16.png`

### Étape 4: Recharger l'extension
1. Ouvrez Chrome
2. Allez à `chrome://extensions/`
3. Trouvez "BookShare Marketplace Checker"
4. Cliquez sur le bouton **🔄 Recharger**

✅ **Fait! L'extension devrait maintenant fonctionner!**

---

## 🔍 Vérifier que les icônes sont créées

Exécutez ce fichier:
```
C:\Users\rannn\Desktop\bookShare\browser-extension\icons\check-icons.bat
```

Il vous dira quelles icônes sont manquantes.

---

## 🎯 Alternative: Utiliser un convertisseur en ligne

Si le générateur HTML ne fonctionne pas:

1. **Allez sur**: https://cloudconvert.com/svg-to-png

2. **Uploadez**: `icon128.svg` (dans le même dossier)

3. **Convertissez** en PNG aux tailles:
   - 128x128 pixels → `icon128.png`
   - 48x48 pixels → `icon48.png`
   - 16x16 pixels → `icon16.png`

4. **Téléchargez** et sauvegardez dans le dossier `icons/`

---

## ✨ Après avoir créé les icônes

L'extension sera prête! Vous pourrez alors:

1. ✅ Charger l'extension dans Chrome
2. ✅ Visiter Amazon.com
3. ✅ Voir les notifications BookShare

---

## 💡 Besoin d'aide?

Vérifiez que les 3 fichiers existent:
- `icons/icon16.png` ← 16x16 pixels
- `icons/icon48.png` ← 48x48 pixels
- `icons/icon128.png` ← 128x128 pixels

**Tous les 3 doivent exister** pour que l'extension fonctionne!
