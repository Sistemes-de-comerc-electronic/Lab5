# Activitat guiada amb IA - Lab 5

Aquest laboratori treballa UUID, autenticació, formularis, visites i reports. Cada tasca ha de tenir PR, proves i revisió crítica de seguretat.

## Entrega per cada tasca

- **Descripció funcional:** què s'ha de fer i per què aporta valor al projecte.
- **Prompt utilitzat:** prompt inicial i prompts de refinament, si n'hi ha.
- **Pla generat per la IA:** pla complet o resum si l'eina no el guarda.
- **Link al PR:** URL del PR amb els commits associats. Pot estar obert o merged.
- **Joc de proves:** casos correctes, errors esperats, codis HTTP, captures, curl/Postman, execució de command o comprovació a BD.
- **Revisió crítica:** què ha fet bé la IA, què heu hagut de corregir i quines decisions són vostres.

## Tasques suggerides

1. Introduir una entitat amb UUID.
2. Crear un formulari o configurar login/autenticació.
3. Registrar visites o generar un report periòdic.

## Exemple de joc de proves

- Usuari autenticat -> 200.
- Usuari no autenticat -> 401 o redirect.
- Usuari sense permisos -> 403.
- Formulari vàlid -> crea o actualitza.
- Formulari invàlid -> mostra error controlat.
