create table input(
    input_id INT(11) NOT NULL AUTO_INCREMENT,
    tipo VARCHAR(120) NOT NULL,
    
    CONSTRAINT pk_input PRIMARY KEY(input_id)
    
)

create table perguntas (
    perguntas_id INT  NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    obrigatorio VARCHAR(200) NOT NULL,
    min_caract INT(3) NOT NULL,
    max_caract INT(3) NOT NULL,
    id_input INT(11) NOT NULL,

    CONSTRAINT pk_perguntas PRIMARY KEY (perguntas_id),
    CONSTRAINT fk_perguntas FOREIGN KEY (id_input) REFERENCES input (input_id)

)

CREATE TABLE multiplo(

    passo_id INT(11) NOT NULL AUTO_INCREMENT,
    valor VARCHAR(100) NOT NULL,
    id_pergunta INT(11) NOT NULL,
    
    CONSTRAINT pk_multiplo PRIMARY KEY (passo_id),
    CONSTRAINT fk_multiplo FOREIGN KEY (id_pergunta) REFERENCES perguntas (perguntas_id)
)


CREATE TABLE formulario(
	
    form_id INT(11) NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descricao VARCHAR(200) NOT NULL,
    
    CONSTRAINT pk_form PRIMARY KEY (form_id)

)

CREATE TABLE form_pergunta(
    
 	perguntas_id int(11) NOT NULL,
    form_id INT(11) NOT NULL,
   
    
    CONSTRAINT pk_form_pergunta PRIMARY KEY (form_id, perguntas_id),
    CONSTRAINT fk_pergunta FOREIGN KEY (perguntas_id) REFERENCES perguntas(perguntas_id),
	CONSTRAINT fk_form FOREIGN KEY (form_id) REFERENCES formulario(form_id)
)


CREATE TABLE resposta(
    resp_id INT(11) NOT NULL AUTO_INCREMENT,
    valor_texto VARCHAR(250),
    valor_data DATE,
    valor_num INT(11),
    id_perguntas INT(11) NOT NULL,
    id_forms INT(11) NOT NULL,
    
    CONSTRAINT pk_resp PRIMARY KEY (resp_id),
    CONSTRAINT fk_pergunta_resposta FOREIGN KEY (id_perguntas) REFERENCES perguntas(perguntas_id),
    CONSTRAINT fk_form_resposta FOREIGN KEY (id_forms) REFERENCES formulario(form_id)
)



