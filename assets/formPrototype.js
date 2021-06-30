import $ from "jquery";

export const formPrototype = (collection,addButton,deleteButton) => {
    const $collection = $(collection);
    const $addButton = $(addButton);
    const $prototype = $($collection.data('prototype'));
    let index =  parseInt($collection.data('index'));

    $('body').on('click', deleteButton , function(){
        console.log(this);
        $(this).parent().parent().parent().remove();
    })

    $addButton.on('click', function(){
        if ($prototype.find('option').length > 0) {
            $collection.append($prototype.html().replace(/__name__/g,index++))
        }
    })
}