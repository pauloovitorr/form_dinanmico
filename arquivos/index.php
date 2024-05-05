<?php 


if($_SERVER['REQUEST_METHOD'] === 'POST'  )

    print_r($_POST);

?>




<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>formulario</title>
    <script src="./js/index.js"></script>
    <link rel="stylesheet" href="./style/estilo.css" >
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    
    <h1>Crie perguntas</h1>

    <form action="" method="post">

        <div>
            
            <label for="perguntaa">Digite sua pergunta</label>
            <input type="text" name="perguntaa" id="perguntaa" minlength="5">  </br></br>

            <label for="inputt">Tipo de input</label>

            <select name="tipo_input" id="inputt" required>

                <option value=""></option>
                <option value="1">Resposta curta</option>
                <option value="2">Parágrafo</option>
                <option value="3">Número</option>
                <option value="4">Data</option>
                <option value="5">Upload de arquivos</option>
                <option value="6">Escolha multipla</option>
                <option value="7">Caixa de seleção</option>
                <option value="8">Lista suspensa</option>

            </select>

        </div>

        <div>
            <h3>Configurações das perguntas</h3>

            <div>
                <label for="">Deve ser obrigatório responder?</label>
                <input type="radio" id="obg_s" name="obrig" value="Sim"> <label for="obg_s">Sim</label>
                <input type="radio" id="obg_n" name="obrig" value="Não"> <label for="obg_n">Não</label>
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

            

        </div>

        <div></br></br>
            <button type="submit">Cadastrar</button>
        </div>

    </form>





    <script>
        $(document).ready(function(){

            
            
            $('#inputt').on('input', function(){

                let valor_input = $('#inputt').val()

                if(valor_input != 1 && valor_input != 2 ){
                    $('.config_caracteres').css('display', 'none')
                }else{
                    $('.config_caracteres').css('display', 'block')
                }

                if(valor_input == 2){
                    $('#minn').attr('min', 21)
                    $('#minn').attr('max', 100)
                    $('#minn').val(25)

                    $('#maxx').attr('min', 120)
                    $('#maxx').attr('max', 200)
                    $('#maxx').val(110)
                }
                else if(valor_input == 1){
                    $('#minn').attr('min', 3)
                    $('#minn').attr('max', 20)
                    $('#minn').val(5)

                    $('#maxx').attr('min', 30)
                    $('#maxx').attr('max', 50)
                    $('#maxx').val(25)

                }

            })





        })
    </script>

</body>
</html>