import $ from "jquery";

export const formPrototype = () => {
    const $collection = $('#pieces_useds_collection');
    const $addButton = $('#add_pieces_used');
    const $prototype = $($collection.data('prototype'));
    let index =  parseInt($collection.data('index'));

    $('body').on('click', '.btn-piece_used_delete', function(){
        console.log(this);
        $(this).parent().parent().parent().remove();
    })

    $addButton.on('click', function(){
        /*if($collection.children().length >= 1){
            const optionId =  $collection.children().eq(-1).find('select').val()
            $prototype.find(`option[value=${optionId}]`).remove()
        }*/

        if ($prototype.find('option').length > 0) {
            $collection.append($prototype.html().replace(/__name__/g,index++))
        }
    })
}