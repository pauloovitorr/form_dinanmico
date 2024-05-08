<?php


require_once('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['perguntaa']) && !empty($_POST['tipo_input']) && !empty($_POST['obrig'])) {

    $pergunta = $conexao->escape_string($_POST['perguntaa']);
    $tipo     = $conexao->escape_string($_POST['tipo_input']);
    $obrig    = $conexao->escape_string($_POST['obrig']);
    $min      = '';
    $max      = '';
    $multiplo_res = 'Não';

    $conexao->begin_transaction();
    try {

        if ($_POST['min'] && $_POST['min']) {
            $min   = $conexao->escape_string($_POST['min']);
            $max   = $conexao->escape_string($_POST['max']);
        }


        if (!empty($_POST['mult'])) {
            $multiplo_res = $conexao->escape_string($_POST['mult']);
        }

        $sql = "INSERT INTO perguntas (titulo, obrigatorio, min_caract, max_caract, id_input, multiplo_res) VALUES (?,?,?,?,?,? ) ";

        $prepare_sql = $conexao->prepare($sql);

        $prepare_sql->bind_param('ssiiis', $pergunta, $obrig, $min, $max, $tipo, $multiplo_res);

        $prepare_sql->execute();

        $id_pergunta = $prepare_sql->insert_id;


        if ($tipo == 6) {
            foreach ($_POST as $nome => $valor) {
                if ($nome != 'perguntaa' && $nome != 'tipo_input' &&  $nome != 'obrig' &&  $nome != 'check1' &&  $nome != 'select1' &&  $nome != 'min' &&  $nome != 'max') {
                    if ($valor != '') {
                        $sql = "INSERT INTO multiplo (valor, id_pergunta) VALUES ('$valor',$id_pergunta )";
                        $conexao->query($sql);
                    }
                }
            }
        } elseif ($tipo == 7) {
            foreach ($_POST as $nome => $valor) {
                if ($nome != 'perguntaa' && $nome != 'tipo_input' &&  $nome != 'obrig' &&  $nome != 'select1' &&  $nome != 'rad1' &&  $nome != 'min' &&  $nome != 'max') {
                    if ($valor != '') {
                        $sql = "INSERT INTO multiplo (valor, id_pergunta) VALUES ('$valor',$id_pergunta )";
                        $conexao->query($sql);
                    }
                }
            }
        } elseif ($tipo == 8) {
            foreach ($_POST as $nome => $valor) {
                if ($nome != 'perguntaa' && $nome != 'tipo_input' &&  $nome != 'obrig' &&  $nome != 'check1' &&  $nome != 'rad1' &&  $nome != 'min' &&  $nome != 'max' && $nome != 'mult') {
                    if ($valor != '') {
                        $sql = "INSERT INTO multiplo (valor, id_pergunta) VALUES ('$valor',$id_pergunta )";
                        $conexao->query($sql);
                    }
                }
            }
        }


        $conexao->commit();
        header("Location: " . $_SERVER['PHP_SELF']);
    } catch (Exception) {
        $conexao->rollback();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cod_pergunta'])) {
    $cod = $_POST['cod_pergunta'];

    $sql = "SELECT p.perguntas_id, p.titulo, p.obrigatorio, p.min_caract, p.max_caract, p.id_input, p.multiplo_res, i.input_id, i.tipo
    FROM perguntas AS p
    INNER JOIN input AS i ON p.id_input = i.input_id
    WHERE p.perguntas_id = $cod";

   $resp = $conexao->query($sql);
   $dados_pergun = [];

   //print_r($resp);

   if($resp->num_rows> 0){

        while($dd = $resp->fetch_assoc()){
            $dados_pergun[] = $dd;
        }

   }

   if($dados_pergun[0]['id_input'] == 6 || $dados_pergun[0]['id_input'] == 7 || $dados_pergun[0]['id_input'] == 8 ){
        $sql2 = "SELECT * FROM multiplo where id_pergunta = $cod";

        $resp2 = $conexao->query($sql2);

        $dados_pergun2 = [];

        if($resp2->num_rows>0){
            while($multi = $resp2->fetch_assoc()){
                $dados_pergun2[] = $multi;
            }

           $dados_pergun[] = $dados_pergun2;
            
        }
       
   }

   $dados_pergunta = json_encode($dados_pergun);

   echo $dados_pergunta;

    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql  = 'SELECT p.perguntas_id, p.titulo, p.obrigatorio, p.min_caract, p.max_caract, p.id_input, p.multiplo_res, i.input_id, i.tipo
     FROM perguntas AS p
     INNER JOIN input AS i ON p.id_input = i.input_id
    
    ';
    $lista_perguntas = $conexao->query($sql);

}



?>




<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>formulario</title>
    <link rel="stylesheet" href="./style/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>

    <h1>Crie perguntas e utilize em vários formulários</h1>

    <div class="container">

        <form action="index.php" method="post">

            <div class="pai_perguntas">

                <div>
                    <label for="perguntaa">Digite sua pergunta</label> <br>
                    <input type="text" name="perguntaa" id="perguntaa" minlength="3" required>
                </div>



                <div>

                    <label for="inputt">Tipo de input</label> <br>
                    <select name="tipo_input" id="inputt" required>

                        <option value=""></option>
                        <option value="1">Resposta curta</option>
                        <option value="2">Parágrafo</option>
                        <option value="3">Número</option>
                        <option value="4">Data</option>
                        <option value="5">Upload de arquivos</option>
                        <option value="6">Escolha única</option>
                        <option value="7">Caixa de seleção</option>
                        <option value="8">Lista suspensa</option>

                    </select>

                </div>

                <div class="dados_input">

                    <div class="pai_multi">
                        <input type="radio" disabled>
                        <input type="text" name="rad1" id="rad1" placeholder="Digite a opção...">
                    </div>

                    <div class="pai_check">
                        <input type="checkbox" disabled>
                        <input type="text" name="check1" id="check1" placeholder="Digite a opção...">
                    </div>

                    <div class="pai_select">
                        <p>1-</p>
                        <input type="text" name="select1" id="select1" placeholder="Digite a opção...">
                    </div>



                    <button id="add"> <i class="fa-solid fa-plus"></i> Adicionar</button>
                </div>

            </div>

            <div class="config_pg">
                <h3>Configurações da pergunta</h3>

                <div>
                    <label for="">Deve ser obrigatório responder?</label>
                    <input type="radio" id="obg_s" name="obrig" value="Sim" required> <label for="obg_s">Sim</label>
                    <input type="radio" id="obg_n" name="obrig" value="Não" required> <label for="obg_n">Não</label>
                </div>

                <div class="config_caracteres">

                    <div>
                        <label for="">Mínimo de caracteres?</label>
                        <input type="number" id="minn" name="min">
                    </div>

                    <div>
                        <label for="">Máximo de caracteres?</label>
                        <input type="number" id="maxx" name="max">
                    </div>

                </div>

                <div class="lista">

                    <div>
                        <label for="">Usuário pode selecionar mais de uma opção?</label>
                        <input type="radio" id="lista_s" name="mult" value="Sim"> <label for="lista_s">Sim</label>
                        <input type="radio" id="lista_n" name="mult" value="Não"> <label for="lista_n">Não</label>


                    </div>

                </div>



            </div>

            <div style="width:15%; margin: 0 auto">
                <button class="btn_perg" type="submit">Cadastrar</button>
            </div>

        </form>
    </div>


    <div class="tabelas container">

        <?php
        if ($lista_perguntas->num_rows > 0) {
            echo '<h2>' . 'Lista de perguntas' . '</h2>';
            echo '<table>';
            echo '<tr>';
            echo '<th>#</th>';
            echo '<th>Pergunta</th>';
            echo '<th>Tipo</th>';
            echo '<th>Lista</th>';
            echo '<th>Obrigatório</th>';
            echo '<th>Mín caracteres</th>';
            echo '<th>Máx caracteres</th>';
            echo '</tr>';

            while ($perg = $lista_perguntas->fetch_assoc()) {

                // print_r($perg);

                // die();

                echo '<tr class="linha_perg">';
                echo '<td class="valor_perg">' . $perg['perguntas_id'] . '</td>';
                echo '<td>' . $perg['titulo'] . '</td>';
                echo '<td>' . $perg['tipo'] . '</td>';
                echo '<td>' . $perg['multiplo_res']  . '</td>';
                echo '<td>' . $perg['obrigatorio']  . '</td>';
                echo '<td>' . ($perg['min_caract'] == 0 ? 'Não possui' : $perg['obrigatorio']) . '</td>';
                echo '<td>' . ($perg['max_caract'] == 0 ? 'Não possui' : $perg['obrigatorio']) . '</td>';
                echo '<td>' . '<i class="fa-solid fa-pen-to-square edit open-modal" ></i>' . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<h2>' . 'Crie suas perguntas' . '</h2>';
        }
        ?>


    </div>


    <!--  Modal -->
    <div id="fade" class="hide"></div>
    <div id="modal" class="hide">
        <div class="modal-header">
            <h2>Este é o Modal</h2>
            <button id="close-modal">Fechar</button>
        </div>

        <div class="modal-body">

        <div class="pai_perguntas">

                <div>
                    <label for="perguntaa">Digite sua pergunta</label> <br>
                    <input type="text" name="perguntaa" id="perguntaaa" minlength="3" required>
                </div>


<!-- Paulo -->
                <div>

                    <label for="inputt">Tipo de input</label> <br>
                    <select name="tipo_input" class="innputt" id="inputty" required>

                        <option value=""></option>
                        <option value="1">Resposta curta</option>
                        <option value="2">Parágrafo</option>
                        <option value="3">Número</option>
                        <option value="4">Data</option>
                        <option value="5">Upload de arquivos</option>
                        <option value="6">Escolha única</option>
                        <option value="7">Caixa de seleção</option>
                        <option value="8">Lista suspensa</option>

                    </select>

                </div>

                <div class="dados_inputt">
                
                </div>

                <div>
                    <button id="addd"> <i class="fa-solid fa-plus"></i> Adicionar</button>
                </div>

            </div>

        </div>
    </div>
    <!-- FIM Modal -->


    <script>
        $(document).ready(function() {



            $('#inputt').on('input', function() {

                let valor_input = $('#inputt').val()

                if (valor_input != 1 && valor_input != 2) {
                    $('.config_caracteres').css('display', 'none')
                } else {
                    $('.config_caracteres').css('display', 'block')
                }

                if (valor_input == 2) {
                    $('#minn').attr('min', 21)
                    $('#minn').attr('max', 100)
                    $('#minn').val(25)

                    $('#maxx').attr('min', 120)
                    $('#maxx').attr('max', 200)
                    $('#maxx').val(125)
                } else if (valor_input == 1) {
                    $('#minn').attr('min', 3)
                    $('#minn').attr('max', 20)
                    $('#minn').val(5)

                    $('#maxx').attr('min', 30)
                    $('#maxx').attr('max', 50)
                    $('#maxx').val(35)

                }


                // config Lista 

                if (valor_input == 8) {
                    $('.lista').css('display', 'block')
                    $('.lista').find('#lista_s').attr('required', true)
                } else {
                    $('.lista').css('display', 'none')
                    $('.lista').find('#lista_s').attr('required', false)
                }

                // config Multiplos

                if (valor_input == 6 || valor_input == 7 || valor_input == 8) {
                    $('.dados_input').css('display', 'block')

                    if (valor_input == 6) {
                        $('.pai_multi').css('display', 'block')
                        $('.pai_multi').find('#rad1').attr('required', true)

                        $('.pai_check').css('display', 'none')
                        $('.pai_select').css('display', 'none')
                    } else {
                        $('.pai_multi').css('display', 'none')
                        $('.pai_multi').find('#rad1').attr('required', false)
                    }


                    if (valor_input == 7) {
                        $('.pai_check').css('display', 'block')
                        $('.pai_check').find('#check1').attr('required', true)

                        $('.pai_multi').css('display', 'none')
                        $('.pai_select').css('display', 'none')
                    } else {
                        $('.pai_check').css('display', 'none')
                        $('.pai_check').find('#check1').attr('required', false)
                    }



                    if (valor_input == 8) {
                        $('.pai_select').css('display', 'block')
                        $('.pai_select').find('#select1').attr('required', true)

                        $('.pai_multi').css('display', 'none')
                        $('.pai_check').css('display', 'none')
                    } else {
                        $('.pai_select').css('display', 'none')
                        $('.pai_select').find('#select1').attr('required', false)
                    }


                } else {
                    $('.dados_input').css('display', 'none')
                    $('.pai_multi').find('#rad1').attr('required', false)
                    $('.pai_check').find('#check1').attr('required', false)
                    $('.pai_select').find('#select1').attr('required', false)
                }


            })




            let cont = 2
            let contador = 2

            $('#add').click(function(e) {

                let valor_input = $('#inputt').val()

                if (valor_input == 6) {
                    e.preventDefault();

                    let div_input_mult = document.querySelector('.pai_multi');

                    let radio = document.createElement('input')
                    radio.type = 'radio'
                    radio.disabled = true


                    let text = document.createElement('input')
                    text.type = 'text'
                    text.name = 'rad' + cont
                    text.placeholder = 'Digite a opção...'
                    text.required = true

                    div_input_mult.appendChild(radio)
                    div_input_mult.appendChild(text)

                    cont++
                } else if (valor_input == 7) {
                    e.preventDefault();

                    let div_input_mult = document.querySelector('.pai_check');

                    let radio = document.createElement('input')
                    radio.type = 'checkbox'
                    radio.disabled = true


                    let text = document.createElement('input')
                    text.type = 'text'
                    text.name = 'check' + cont
                    text.placeholder = 'Digite a opção...'
                    //text.required = true

                    div_input_mult.appendChild(radio)
                    div_input_mult.appendChild(text)

                    cont++
                } else if (valor_input == 8) {

                    e.preventDefault();

                    let div_input_select = document.querySelector('.pai_select');

                    let p = document.createElement('p')
                    p.innerHTML = contador + '  -  '


                    let text = document.createElement('input')
                    text.type = 'text'
                    text.name = 'select' + cont
                    text.placeholder = 'Digite a opção...'


                    div_input_select.appendChild(p)
                    div_input_select.appendChild(text)

                    cont++
                    contador++
                }

            });


            // MODAL
            //const openModalButton = document.querySelector("#open-modal");
            const closeModalButton = document.querySelector("#close-modal");
            const modal = document.querySelector("#modal");
            const fade = document.querySelector("#fade");

            const toggleModal = () => {
                modal.classList.toggle("hide");
                fade.classList.toggle("hide");
            };

            $('.open-modal').click(function(){
                let cod_per = $(this).closest('.linha_perg').find('.valor_perg').text()
                
                toggleModal()

                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {cod_pergunta : cod_per},
                    success: function(res){

                        //console.log(res[0].max_caract)
                        
                        $('#perguntaaa').val(res[0].titulo)
                        $('#inputty').val(res[0].id_input)

                        let div_container = document.querySelector('.dados_inputt')

                        if(res[1] != 0 &&  res[1] != undefined){
                            
                            div_container.innerHTML = ''
                            

                            
                            let filho = div_container.querySelectorAll('.input_radio')

                            

                                filho.forEach(function(elemento) {
                                    elemento.parentNode.removeChild(elemento);
                                });

                
                                let conta = 1
                                for(let i of res[1]){

                                    div = document.createElement('div')
                                    div.classList = 'input_radio'

                                    if(res[0].tipo == 'radio' || res[0].tipo == 'checkbox'){
                                    let input = document.createElement('input')
                                    input.type = res[0].tipo
                                    input.disabled = true
                                    

                                    let input_text = document.createElement('input')
                                    input_text.type = 'text'
                                    input_text.value = i.valor

                                    div.appendChild(input)
                                    div.appendChild(input_text)
                                    
                                    }
                                    else if(res[0].tipo == 'select'){

                                        let input_select = document.createElement('input')
                                        input_select.type = 'text'
                                        input_select.value = i.valor

                                        let p = document.createElement('p')
                                        p.classList = 'p_ordem'
                                        p.innerHTML = conta + ' - '

                                        div.appendChild(p)
                                        div.appendChild(input_select)
                                    }

                                    div_container.appendChild(div)
                                    conta++
                                    }   
                        }
                        else{

                        let obrig = `<div class="listaa">

                        <div>
                            <label for="">Deve ser obrigatório responder?</label>
                            <input type="radio" id="obg_s" name="obrig" value="Sim" required> <label for="obg_s">Sim</label>
                            <input type="radio" id="obg_n" name="obrig" value="Não" required> <label for="obg_n">Não</label>
                        </div>


                            </div>`

                         let tt =  ` <div class="config_caracteres">

                                <div>
                                    <label for="">Mínimo de caracteres?</label>
                                    <input type="number" id="minnn" name="min" min="${res[0].min_caract}" max="${parseInt(res[0].min_caract) +10}"  value= "${res[0].min_caract}"   >
                                </div>

                                <div>
                                    <label for="">Máximo de caracteres?</label>
                                    <input type="number" id="maxxx" name="max" min="${parseInt(res[0].min_caract) + 11}"  max="${res[0].max_caract}" value= "${res[0].max_caract}">
                                </div>

                                </div>`

                                div_container.innerHTML = obrig
                                div_container.innerHTML += tt

                        }


                        
                    }
                })


            })

            $("#close-modal").click(function(){
                toggleModal()
            })

            $("#fade").click(function(){
                toggleModal()
            })
                
           



            $('.innputt').on('input', function(){
               let valor = $(this).val()
               
                let cont = 0

               if (valor == 6) {
                document.querySelector('.dados_inputt').innerHTML = ''

                } else if (valor == 7) {
                    document.querySelector('.dados_inputt').innerHTML = ''
                    
                } else if (valor == 8) {
                    document.querySelector('.dados_inputt').innerHTML = ''

                }
                else{

                    document.querySelector('.dados_inputt').innerHTML = `<div class="listaa">
                                                                    <div>
                                                                        <label for="">Deve ser obrigatório responder?</label>
                                                                        <input type="radio" id="obg_s" name="obrig" value="Sim" required> <label for="obg_s">Sim</label>
                                                                        <input type="radio" id="obg_n" name="obrig" value="Não" required> <label for="obg_n">Não</label>
                                                                    </div>

                                                                        </div>`
                
                }

            

            })















            $('#addd').click(function(e) {

                e.preventDefault();

    let valor_input = $('#inputty').val()


    var paragrafos = document.querySelectorAll('.p_ordem');

    if(paragrafos.length > 0){
        var ultimoParagrafo = paragrafos[paragrafos.length - 1].textContent;
        ultimoParagrafo = ultimoParagrafo.split('')

        ultimoParagrafo = parseInt(ultimoParagrafo) + 1
        
    }

    


if (valor_input == 6) {
   

    let div_input_mult = document.querySelector('.dados_inputt');
    let divv = document.createElement('div')
    divv.classList = 'input_radio'

    let radio = document.createElement('input')
    radio.type = 'radio'
    radio.disabled = true


    let text = document.createElement('input')
    text.type = 'text'
    text.name = 'rad' + cont
    text.placeholder = 'Digite a opção...'
    text.required = true

    divv.appendChild(radio)
    divv.appendChild(text)

    div_input_mult.appendChild(divv)

    cont++
} else if (valor_input == 7) {
    
   
    let div_input_mult = document.querySelector('.dados_inputt');
    let divv = document.createElement('div')
    divv.classList = 'input_radio'

    let check = document.createElement('input')
    check.type = 'checkbox'
    check.disabled = true


    let text = document.createElement('input')
    text.type = 'text'
    text.name = 'check' + cont
    text.placeholder = 'Digite a opção...'
    //text.required = true

    divv.appendChild(check)
    divv.appendChild(text)

    div_input_mult.appendChild(divv)

    cont++
} else if (valor_input == 8) {

    let div_input_mult = document.querySelector('.dados_inputt');
    let divv = document.createElement('div')
    divv.classList = 'input_radio'

    



    let text = document.createElement('input')
    text.type = 'text'
    text.name = 'select' + cont
    text.placeholder = 'Digite a opção...'


   divv.appendChild(p)
   divv.appendChild(text)

   div_input_mult.appendChild(divv)

    cont++
    contador++
    ultimoParagrafo++
}

});
        })
    </script>

</body>

</html>