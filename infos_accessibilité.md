1) sur l'outline:none - L'outline est super important pour les malvoyants, ma suggestion c'est pas seulement ne pas mettre le "none" mais plutôt surenchérir. Un exemple de surenchère plutôt réussite c'est ici : http://references.modernisation.gouv.fr/referentiel/criteres.html
D'ailleurs, je conseille les étudiants à lire le RGAA 3 technique, malgré que c'est un texte de loi, il est light et parle d'inclusion, etc

2) sur le module 10, on a un form sans label,  L'idée c'est de garder les labels pour les personnes qui utilisent les lecteurs d'écran (non-voyants pour la plupart) car ces logiciels ils lisent le code et ont besoin des labels pour bien interpréter les formulaires. Et la position absolute permet de cacher ces labels pour les voyants. Pour info, les principaux lecteurs d'écran sont NVDA (PC), Jaws et le chiantissime VoiceOver (Mac) et ils adorent les labels

3) Si vous allez dans le site gouvernement.fr mode desktop, vous allez trouver dès l'ouverture du header la div suivante :
```
<div id="liens-acces-rapide">
	<a href="/accueil-gouvernementfr#content" tabindex="1">Aller au contenu</a>
	<a href="/accueil-gouvernementfr#search-block-form" tabindex="2">Aller à la recherche</a>
	<a href="/accueil-gouvernementfr#nav" tabindex="3">Aller au menu</a>
</div>
```


il s'agit d'une div cachée spéciale pour les personnes non-voyantes car elle permet d'accéder rapidement au contenu du site, par exemple, sans se taper tout le header, les liens réseaux sociaux, etc (edited)
ces encres sont super utiles, imaginez être obligé de lire le mega menu de la redoute avant d'accéder au contenu...Ces encres sont là pour éviter ça
Par contre, elles sont en display:none dans la version mobile, car le display:none fait que la balise devienne muette aux lecteurs d'écran (et un non-voyant ne peut pas utiliser une tablette ou un smartphone pour des motifs évidents)

4) un attribut alt vide (alt="") fait que l'image devienne muette aussi, du coup, super utile pour cacher des images décoratives et qui ne sont pas porteuses d'information (je pense à la vignette de Fish&Chips) (edited)