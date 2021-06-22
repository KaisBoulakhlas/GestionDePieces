export const handleChangeType = () => {

    const pieceSelect = document.querySelector('#piece_type');
    pieceSelect && pieceSelect.addEventListener('change', () => {

        const piecePrice = document.querySelector('.form_piece_price');
        const piecePriceCatalog = document.querySelector('.form_piece_price_catalog');
        const pieceRange = document.querySelector('.form_piece_range');
        const pieceProvider = document.querySelector('.form_piece_provider');
        const pieceCheckPiecesChildren = document.querySelector('.form-pieces-children');
        const pieceSelected = pieceSelect.selectedIndex;

        if(pieceSelected === 1 || pieceSelected === 2){
            piecePriceCatalog.hasAttribute('hidden') && piecePriceCatalog.removeAttribute('hidden');
            pieceProvider.removeAttribute('hidden');
            pieceCheckPiecesChildren.setAttribute('hidden','hidden');
            //si on revient sur l'option et que les input prix et gamme n'ont plus l'attribut hidden,
            // on recache l'input prix et gamme
            !piecePrice.hasAttribute('hidden') && piecePrice.setAttribute('hidden','hidden');
            !pieceRange.hasAttribute('hidden') && pieceRange.setAttribute('hidden','hidden');

        } else if (pieceSelected === 3) {
            piecePrice.setAttribute('hidden', 'hidden');
            piecePriceCatalog.setAttribute('hidden', 'hidden');
            pieceCheckPiecesChildren.removeAttribute('hidden');
            //si on revient sur l'option et que les input fournisseur et gamme n'ont plus l'attribut hidden,
            // on recache l'input fournisseur et gamme
            !pieceProvider.hasAttribute('hidden') && pieceProvider.setAttribute('hidden','hidden');
            !pieceRange.hasAttribute('hidden') && pieceRange.setAttribute('hidden','hidden');
        } else if(pieceSelected === 4) {
            //si on revient sur l'option et que les input fournisseur et prix catalogue n'ont plus l'attribut hidden,
            // on recache l'input fournisseur et prix catalogue
            !pieceProvider.hasAttribute('hidden') && pieceProvider.setAttribute('hidden','hidden');
            !piecePriceCatalog.hasAttribute('hidden') && piecePriceCatalog.setAttribute('hidden','hidden');
            piecePrice.removeAttribute('hidden');
            pieceRange.removeAttribute('hidden');
            pieceCheckPiecesChildren.removeAttribute('hidden');
        } else {
            //Sur l'option par défaut, prix, prix catalogue, fournisseur ont l'attribut hidden
            piecePrice.setAttribute('hidden','hidden');
            piecePriceCatalog.setAttribute('hidden','hidden');
            pieceProvider.setAttribute('hidden','hidden');
            pieceRange.setAttribute('hidden','hidden');
            pieceCheckPiecesChildren.setAttribute('hidden','hidden');
        }
    })
}