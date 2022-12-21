
//========================================================
// Скрипт с рисовкой (изменением) эскиза к странице заказа
//========================================================




$('#save_img').on('click', function (){

    var id_bd = $('#id_bd').val().trim();
    var id_order = $('#id_order').val().trim();
    var room = $('#room').val().trim();
    var page_id = $('#page_id').val().trim();
    var specification = $('#specification').val().trim();
    var img_s = canvas.toDataURL('image.png').replace('data:image/png;base64,', '');

    console.log(id_order);


    $.ajax({
        url: '../save_new_img_to_db.php',
        type: "POST",
        data: {
            img_s: img_s,

            id_bd: id_bd,
            id_order: id_order,
            room: room,
            page_id: page_id,
        },
        dataType: 'html',
        success: function (){
            document.location.href = 'sketches_room_update.php?id_in_sketches=' + id_bd;
        },error: function(request, status, error){
            alert("Error: Ошибка сохранения");
        }
    });
});


let canvas = document.getElementById("c1_new");
let context = canvas.getContext("2d");

var img = new Image();
var height = 180;
var width = 180;
img.onload = function() {
    context.save();
    context.rect(0, 0, 1000, 500);//Здесь первый 0=X
    context.clip();
    context.drawImage(img,0, 0,1000,500);//Первый 0=X
    context.restore();
};




var id_bd = $('#id_bd').val().trim();
var id_order = $('#id_order').val().trim();
var room = $('#room').val().trim();
var page_id = $('#page_id').val().trim();

console.log(id_bd, id_order, room, page_id);

var d = new Date();


img.src = '../assets/img/img_' + id_order + "_" + room + "_" + page_id + ".png?ver=" + d.getTime();



let stroke_color = 'black';
let stroke_width = "3";
let is_drawing = false;
let fig;


document.getElementById('color').oninput = function (){
    stroke_color = this.value;
}

function change_color(element) {
    stroke_color = element.style.background;
}

function change_width(element) {
    stroke_width = element.innerHTML
}






function Clear() {
    context.fillStyle = "white";
    context.clearRect(0, 0, canvas.width, canvas.height);

    img.src = canvas.toDataURL();

    context.fillRect(0, 0, canvas.width, canvas.height);
    restore_array = [];
    start_index = -1;
}




let restore_array = [];
let start_index = -1;
function undo_last(){
    if(start_index <= 0){
        img.src = '../assets/img/img_' + id_order + "_" + room + "_" + page_id + ".png?ver=" + d.getTime();
    }else{
        start_index -= 1;
        restore_array.pop();
        context.putImageData(restore_array[start_index], 0, 0);
        console.log(restore_array);
    }
}






var f1 = 0;
var f2 = 0;
var f3 = 0;

function d_1(){

    fig = 0;

    if(f1 == 1){
        return;
    }
    f1 = 1;



    console.log(fig);

    canvas.addEventListener("touchstart", start, false);
    canvas.addEventListener("touchmove", draw, false);
    canvas.addEventListener("touchend", stop, false);
    canvas.addEventListener("mousedown", start, false);
    canvas.addEventListener("mousemove", draw, false);
    canvas.addEventListener("mouseup", stop, false);
    canvas.addEventListener("mouseout", stop, false);


    function getX(event) {
        if (event.pageX == undefined) {return event.targetTouches[0].pageX - canvas.offsetLeft}
        else {return event.pageX - canvas.offsetLeft}
    }
    function getY(event) {
        if (event.pageY == undefined) {return event.targetTouches[0].pageY - canvas.offsetTop}
        else {return event.pageY - canvas.offsetTop}
    }
    // обычная рисовалка
    function start(event) {
        if(fig == 0) {
            is_drawing = true;
            context.beginPath();
            context.moveTo(getX(event), getY(event));
            event.preventDefault();
        }
    }
    function draw(event) {
        if (is_drawing && fig == 0) {
            context.lineTo(getX(event), getY(event));
            context.strokeStyle = stroke_color;
            context.lineWidth = stroke_width;
            context.lineCap = "round";
            context.lineJoin = "round";
            context.stroke();
        }
        event.preventDefault();
    }
    function stop(event) {
        if (is_drawing) {
            context.stroke();
            context.closePath();
            is_drawing = false;
        }
        event.preventDefault();

        if(event.type != 'mouseout' && event.type != 'touchend' && fig == 0){
            restore_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            start_index += 1;
            console.log(restore_array);
        }

        img.src = canvas.toDataURL();
    }
}













function d_2(){

    fig = 1;

    if(f2 == 1){
        return;
    }
    f2 = 1;



    console.log(fig);
    canvas.addEventListener("mousedown", startDrawing, false);
    canvas.addEventListener("mousemove", draw_2, false);
    canvas.addEventListener("mouseup", stopDrawing, false);

    canvas.addEventListener("touchstart", startDrawing, false);
    canvas.addEventListener("touchmove", draw_2, false);
    canvas.addEventListener("touchend", stopDrawing, false);
    canvas.addEventListener("mouseout", stopDrawing, false);



    isDrawing = false;
    function getX(e) {
        if (e.pageX == undefined) {return e.targetTouches[0].pageX - canvas.offsetLeft}
        else {return e.pageX - canvas.offsetLeft}
    }
    function getY(e) {
        if (e.pageY == undefined) {return e.targetTouches[0].pageY - canvas.offsetTop}
        else {return e.pageY - canvas.offsetTop}
    }
    function current_coords(e){
        x2 = getX(e);
        y2 = getY(e);
    }

    function startDrawing(e) {
        if(e.which == 1 || e.which == 0){
            isDrawing = true;


            context.beginPath();
            context.moveTo(getX(e), getY(e));
            e.preventDefault();


        }
        x = getX(e);
        y = getY(e);
    }


    function stopDrawing(e) {
        img.src = canvas.toDataURL();
        isDrawing = false;

        if(e.type != 'mouseout' && e.type != 'touchend' && fig == 1){
            restore_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            start_index += 1;
            console.log(restore_array);
        }
    }

    function draw_2(e) {
        if(isDrawing == true && fig == 1){
            context.clearRect(0,0,canvas.width, canvas.height);
            context.drawImage(img, 0, 0);
            current_coords(e);
            draw_ellipce();
            context.strokeStyle = stroke_color;
            context.lineWidth = stroke_width;
            context.stroke();
        }
        e.preventDefault();
    }
    function current_center_radius(){
        if(x2 > x || x2 == x){
            R_x = (x2 - x) / 2;
            centerX = R_x + x;
        }
        if(x2 < x){
            R_x = (x - x2) / 2;
            centerX = x - R_x;
        }

        if(y2 > y || y2 == y){
            R_y = (y2 - y) / 2;
            centerY = R_y + y;
        }
        if(y2 < y){
            R_y = (y - y2) / 2;
            centerY = y - R_y;
        }
    }

    function draw_ellipce() {
        current_center_radius();
        context.save();

        if(R_x > R_y || R_x == R_y) {
            R = R_x;
            scale_y = R_y / R_x;
            scale_x = 1;
            if(scale_y != 0){
                centerY = centerY / scale_y;
            }
            context.scale(1, scale_y);
        }
        if(R_x < R_y) {
            R = R_y;
            scale_x = R_x / R_y;
            scale_y = 1;
            if(scale_x != 0){
                centerX = centerX / scale_x;
            }
            context.scale(scale_x, 1);
        }


        context.beginPath();
        context.translate(centerX, centerY);
        if(scale_y != 0 && scale_x != 0){
            context.arc(0,0, R, 0, 2*Math.PI);
        }
        context.restore();
        context.stroke();

    }


}







function d_3(){

    fig = 2;

    if(f3 == 1){
        return;
    }
    f3 = 1;


    console.log(fig);
    canvas.addEventListener("mousedown", startDrawing, false);
    canvas.addEventListener("mousemove", draw_3, false);
    canvas.addEventListener("mouseup", stopDrawing, false);
    canvas.addEventListener("touchstart", startDrawing, false);
    canvas.addEventListener("touchmove", draw_3, false);
    canvas.addEventListener("touchend", stopDrawing, false);
    canvas.addEventListener("mouseout", stopDrawing, false);



    function current_width_height(){
        rect_width = Math.abs(x2 - x);
        rect_height = Math.abs(y2 - y);
    }
    function drawRect() {
        current_width_height();
        context.beginPath();
        if (x2 < x) x_start = x2;
        if (y2 < y) y_start = y2;
        if (x2 > x) x_start = x;
        if (y2 > y) y_start = y;
        if (x2 == x) x_start = x;
        if (y2 == y) y_start = y;
        context.rect(x_start, y_start, rect_width, rect_height);
        context.stroke();
    }
    isDrawing = false;
    function getX(e) {
        if (e.pageX == undefined) {return e.targetTouches[0].pageX - canvas.offsetLeft}
        else {return e.pageX - canvas.offsetLeft}
    }
    function getY(e) {
        if (e.pageY == undefined) {return e.targetTouches[0].pageY - canvas.offsetTop}
        else {return e.pageY - canvas.offsetTop}
    }
    function current_coords(e){
        x2 = getX(e);
        y2 = getY(e);
    }

    function startDrawing(e) {
        if(e.which == 1 || e.which == 0){
            isDrawing = true;
        }
        x = getX(e);
        y = getY(e);
    }
    function stopDrawing(e) {
        img.src = canvas.toDataURL();
        isDrawing = false;

        if(e.type != 'mouseout' && e.type != 'touchend' && fig == 2){
            restore_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            start_index += 1;
            console.log(fig, restore_array);
        }
    }
    function draw_3(e) {
        if(isDrawing == true && fig == 2){
            context.clearRect(0,0,canvas.width, canvas.height);
            context.drawImage(img, 0, 0);
            context.strokeStyle = stroke_color;
            context.lineWidth = stroke_width;
            current_coords(e);
            drawRect();
        }
    }
}

