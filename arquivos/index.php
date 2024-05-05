<?php


require_once('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['perguntaa']) && !empty($_POST['tipo_input']) && !empty($_POST['obrig'])) {

    // print_r($_POST);

    // die();

    $pergunta = $conexao->escape_string($_POST['perguntaa']);
    $tipo     = $conexao->escape_string($_POST['tipo_input']);
    $obrig    = $conexao->escape_string($_POST['obrig']);
    $min      = '';
    $max      = '';
    $multiplo_res = '';

    //print_r($_POST);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/fontawesome.min.css" integrity="sha512-UuQ/zJlbMVAw/UU8vVBhnI4op+/tFOpQZVT+FormmIEhRSCnJWyHiBbEVgM4Uztsht41f3FzVWgLuwzUqOObKw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>

    <h1>Crie perguntas</h1>

    <div class="container">

        <form action="index.php" method="post">

            <div class="pai_perguntas">

                <div>
                    <label for="perguntaa">Digite sua pergunta</label> <br>
                    <input type="text" name="perguntaa" id="perguntaa" minlength="5" required>
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
                        <input type="text" name="rad1" id="rad1" placeholder="Digite a opção..." >
                    </div>

                    <div class="pai_check">
                        <input type="checkbox" disabled>
                        <input type="text" name="check1" id="check1" placeholder="Digite a opção..." >
                    </div>

                    <div class="pai_select">
                        <p>1 - </p>
                        <input type="text" name="select1" id="select1" placeholder="Digite a opção..." >
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

            <div>
                <button type="submit">Cadastrar</button>
            </div>

        </form>
    </div>


    <div>
        <table>

            <tr>
                <th>Pergunta</th>
                <th>Tipo</th>
                <th>Obrigatório</th>
                <th>Mín caracteres</th>
                <th>Máx caracteres</th>
            </tr>

            <?php

            if ($lista_perguntas->num_rows > 0) {

                while ($perg = $lista_perguntas->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $perg['titulo']  . '</td>';
                    echo '<td>' . $perg['tipo']  . '</td>';
                    echo '<td>' . $perg['obrigatorio']  . '</td>';
                    echo '<td>' . $perg['min_caract']  . '</td>';
                    echo '<td>' . $perg['max_caract']  . '</td>';
                    echo '</tr>';
                }
            }

            ?>

        </table>
    </div>



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

                    if(valor_input == 6){
                        $('.pai_multi').css('display', 'block')
                        $('.pai_multi').find('#rad1').attr('required', true)

                        $('.pai_check').css('display', 'none')
                        $('.pai_select').css('display', 'none')
                    }else{
                        $('.pai_multi').css('display', 'none')
                        $('.pai_multi').find('#rad1').attr('required', false)
                    }


                    if(valor_input == 7){
                        $('.pai_check').css('display', 'block')
                        $('.pai_check').find('#check1').attr('required', true)

                        $('.pai_multi').css('display', 'none')
                        $('.pai_select').css('display', 'none')
                    }else{
                        $('.pai_check').css('display', 'none')
                        $('.pai_check').find('#check1').attr('required', false)
                    }



                    if(valor_input == 8){
                        $('.pai_select').css('display', 'block')
                        $('.pai_select').find('#select1').attr('required', true)

                        $('.pai_multi').css('display', 'none')
                        $('.pai_check').css('display', 'none')
                    }else{
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
                }
                else if (valor_input == 8) {
                    
                    e.preventDefault();

                    let div_input_select = document.querySelector('.pai_select');

                    let p = document.createElement('p')
                    p.innerHTML = contador + '  -'


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










        })
    </script>

</body>

</html>