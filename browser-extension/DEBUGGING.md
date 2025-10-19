# 🔍 Guide de Débogage - Extension BookShare

## Étape 1: Ouvrir la Console du Navigateur

Sur la page Amazon où vous êtes:
1. Appuyez sur **F12** (ou Clic droit → Inspecter)
2. Cliquez sur l'onglet **Console**
3. Rechargez la page (F5)
4. Cherchez les messages qui commencent par "BookShare Extension:"

## Étape 2: Messages attendus

Vous devriez voir quelque chose comme:
```
BookShare Extension: Book detected {title: "It Ends with Us", author: "Colleen Hoover", ...}
BookShare Extension: Availability data {available: false, ...}
```

## Étape 3: Si vous ne voyez RIEN

Cela signifie que le content script ne se charge pas. Vérifiez:
1. Allez dans `chrome://extensions/`
2. Trouvez "BookShare Marketplace Checker"
3. Cliquez sur "Erreurs" s'il y en a
4. Cliquez sur le bouton **🔄 Recharger** l'extension

## Étape 4: Si vous voyez des erreurs

### Erreur type 1: "Cannot read property of null"
- Le sélecteur DOM pour extraire le titre/auteur ne fonctionne plus
- Amazon a changé sa structure HTML

### Erreur type 2: "Failed to fetch" ou "CORS"
- Le serveur Laravel n'est pas accessible
- CORS mal configuré

### Erreur type 3: "extractBookInfo returned null"
- L'extension ne détecte pas qu'elle est sur une page de livre
- Les sélecteurs CSS ne trouvent pas les éléments

## Étape 5: Test manuel via la Console

Dans la console Chrome, tapez:
```javascript
// Tester l'extraction du titre
document.querySelector('#productTitle')?.textContent.trim()

// Tester l'extraction de l'auteur
document.querySelector('.author .a-link-normal')?.textContent.trim()

// Test complet
console.log({
  title: document.querySelector('#productTitle')?.textContent.trim(),
  author: document.querySelector('.author .a-link-normal')?.textContent.trim()
})
```

Résultat attendu:
```
{
  title: "It Ends with Us",
  author: "Colleen Hoover"
}
```

## Étape 6: Vérifier les requêtes réseau

1. Dans DevTools, onglet **Network**
2. Filtrez par "check-availability"
3. Rechargez la page
4. Voyez-vous une requête vers `http://127.0.0.1:8000/api/public/books/check-availability`?
   - ✅ OUI → L'API est appelée, regardez la réponse
   - ❌ NON → Le content script ne s'exécute pas

## Étape 7: Debugger le Popup

1. Cliquez sur l'icône de l'extension
2. Dans le popup qui s'ouvre, clic droit → **Inspecter**
3. Une nouvelle fenêtre DevTools s'ouvre pour le popup
4. Regardez la console pour les erreurs

## Solutions Rapides

### Si rien ne se passe:
```bash
# Rechargez l'extension
1. chrome://extensions/
2. Trouvez BookShare
3. Cliquez sur 🔄 Recharger
```

### Si "Error checking availability":
```bash
# Vérifiez que le serveur tourne
curl http://127.0.0.1:8000/api/public/books/check-availability?title=Test
```

### Si le livre n'est pas détecté:
- Vous n'êtes peut-être pas sur une page de détail de livre
- Essayez sur une autre page de livre Amazon
- Les sélecteurs CSS ont peut-être changé

## Informations à me fournir

Pour que je puisse vous aider, envoyez-moi:
1. ✅ Messages de console (onglet Console)
2. ✅ Erreurs de l'extension (chrome://extensions/)
3. ✅ Requêtes réseau (onglet Network, filtre "check-availability")
4. ✅ Résultat des tests manuels JavaScript ci-dessus

---

**Une fois que vous me donnez ces infos, je pourrai corriger exactement le problème! 🔧**
