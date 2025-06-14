# Origamid

### CMS (Content Management System)

O conteúdo do site (texto, imagens, etc) são buscadas em um banco de dados, o usuário só precisa acessar a CMS UI para setar os dados que ele quer que apareça no site. Reduz a quantidade de código, separa código e de conteúdo.

### [wordpress.com](http://wordpress.com) X wordpress.org

- O **.org** é gratuito e livre, mas precisa ser hospedado.
- Enquanto o **.com** é gratuito até certo ponto (para fazer mais personalizações), mas não precisa ser hospedado.

## PHP (Personal Home Page)

Todo código PHP está dentro de um bloco PHP:

```php
<?php wp_head(); ?>
```

## Estrutura do projeto no wordpress

Na pasta app/public temos o arquivo:

- wp-config.php: onde está as configurações básicas do projeto, como nome do usuário, banco de dados, senhas, etc. Podemos querer mudar algo com o passar do tempo.
- Em wp-content/themes temos a estrutura visual do nosso projeto.

## Instruções

```php
// Declarando variáveis:
<?php
	$myvar = 'Minha Var'; // pode usar dupla ou simples
	$myarray = array(5, 2, 3, 4); // aqui nao é necessário usar ;, mas é legal

	echo $myvar; // printa o valor de $myvar
?>

// Condicionamento
<?php if ($minhacondicao) { ?>
	<p>....
<?php } else { ?>
	<p>....
<?php } ?>

// Loops
<?php while (condicao) : ?> // = até podemos usar chaves, mas assim é mais comum
	<p>....
<?php endWhile ?>
```

## Novo tema no wordpress

Um tema precisa ter no mínimo:

- um arquivo style.css na raiz do tema. Com um cabeçalho informando seus dados (obrigatório)
- um arquivo index.php na raiz.
- Para ter uma thumbnaile no wordpress é necessário ter uma imagem na raiz chamada screenshot, com o tamanho 800x600.

## Algumas funções do wordpress

### Funções para estruturar a página

- `get_stylesheet_directory_uri()`: onde está o estilo do site.
- `get_template_directory_uri()`: onde está o template do site. Normalmente é o mesmo caminho da função anterior. Outra forma de fazer isso é usar a constante `TEMPLATEPATH` .
- `get_header()`: injeta o conteúdo que está no arquivo header.php.
    - `get_footer()`
- `wp_head()`: deve ser adicionada logo antes de `</head>`, para injetar várias dependências do wordpress
    - `wp_footer()` deve ser inserida logo antes de `</body>`. É quem gera a admin navigation bar.
- `bloginfo(’name’)`: injeta o nome do site.
- `include(path)`: é usado para injetar o conteúdo de outro arquivo php. Normalmente os componentes ficam em na pasta inc/.
- `require_once` é usado para carregar arquivos PHP.
- `is_page()`: pode receber um argumento ou não, no primeiro caso verifica se é a página ou uma das páginas informadas (pode-se passar o ID dela, título, ou slug), e no segundo caso verifica se é uma página.

### Funções para trabalhar com O Loop

- `have_posts()`: existe posts?
- `the_post()`: usado para consumir um post.
    - Deve funcionar como uma fila, essa função realiza um *pop* e coloca no contexto as informações do post removido para ser usado internamente no loop. A próxima vez que a função for usada, outro post será selecionado, até que não reste nenhum post.
- `the_title()`: injeta o título do post
- `the_content()`: injeta o conteúdo do post

### Outras funções

- `date("Y")`: retorna o ano atual. Como argumento pode-se passar qualquer string indicando o formato que deve ser retornado a data.
- É legal ter toda página com um título diferente. Exemplo: `<?php bloginfo('name'); ?> | <?php wp_title(''); ?> - <?php the_field('titulo_seo') ?>`. O mesmo conteúdo pode ser usado na meta tag `description`, mas é interessante substituir `title_seo` por `desciption_seo`(mais longo).

## Template de página

Os templates seguem uma hierarquia, onde se um template não é encontrado quando tentado usá-lo, é usado o próximo template (o index.php é o último na hierarquia).

- index.php não deve possuir conteúdo estático.
- Para usar um custom-template devemos inserir o cabeçalho a seguir no ínico do arquivo:
    - Pode-se usar comentário de bloco também.
    - O nome do template deve ser único
    
    ```php
    <?php
    // Template Name: Nome do Template
    ?>
    ```
    
- Para usar o template criamos novas páginas pelo wordpress e alteramos o template pela opção na sidebar.
- Para alterar a página inicial do site vamos em settings/reading e alteramos a seguinte opção:
    
    ![image.png](Origamid%201c483744f87d8052ada5e8260868e36a/image.png)
    

## O Loop

É recomendado usá-lo dentro de todo template.

É usado para percorrer todos os posts/páginas do site e exibir seu conteúdo. Um forma básica de implementá-lo é usar:

```php
<?php if (have_posts()): while (have_posts()): the_post(); ?>
	<h1>Título do post....
	<p>Conteúdo...
<?php endwhile; else: ?>
	<p>Não há posts...
<?php endif; ?>
```

## Custom Fields (Nativo)

Na página onde será criado *custom fields* deve-se clicar em Opções de tela e Campos personalizados. Depois inserir os campos desejados. Para usá-los no código deve-se fazer o seguinte:

![image.png](Origamid%201c483744f87d8052ada5e8260868e36a/image%201.png)

## Custom Fields com CMB2

CMB2 é um plugin do Wordpress.

Configuramos os campos no arquivo functions.php, esse arquivo é usado definir funções que serão executadas em qualquer página do site. Devemos criar uma função (ou várias, uma para cada página) com nome único onde faremos nossa configuração, e definimos uma ação para executar a função sempre que a função **cmb2_admin_init** for executada. Por exemplo:

![image.png](Origamid%201c483744f87d8052ada5e8260868e36a/image%202.png)

Primeiramente definimos uma caixa (conjunto de *custom fields* dentro de uma template) da seguinte maneira:

```php
// atribuímos a uma variável pois ela será usada na criação dos campos
$cmb2_home = new_cmb2_box([
	'id' => 'home_box', // id da caixa
	'title' => 'Home', // Label da caixa (título dela na UI do wordpress)
	'object_types' => ['page'], // tipo de local onde vai aparecer (page, post)
	'show_on' => [ // a caixa vai aparecer no template definido em page-home.php
		'key' => 'page-template',
		'value' => 'page-home.php',
	]
]);
```

Para criar um campoo usamos

```php
$cmb2_home->add_field([
	'name' => 'Menu da Semana',
	'id' => 'menu',
	'type' => 'text'.
]);
```

E para usar no template usamos da mesma forma nativa.

Podemos criar duas funções **get_field()** e **the_field()** para deixar mais alto nível o uso de *custom fields* no template. Por exemplo:

```php
function get_field($field, $page = NULL, $unique = true) {
	$page = is_null($page) ? get_the_ID() : $page;
	return get_post_meta($page, $field, $unique);
}
function the_field($field, $page = NULL, $unique = true) {
	$page = is_null($page) ? get_the_ID() : $page;
	echo get_field($field, $page);
}
```

Em `type` podemos passar diversas opções diferentes, por exemplo `file` para inserir uma imagem ou outro tipo de arquivo. Para iserir uma imagem fazemos:

```php
$cmb2_home->add_field([
	'name' => 'Foto Rest',
	'id' => 'foto_rest',
	'type' => 'file',
	'options' => [
		'url' => false, // o usuário não vê a URL do arquivo
	]
])
```

E para usar a imagem no template usamos:

```php
/*
* adicionamos '_id' no final do nome do campo pois estamos indicando para o
* cmb2 que retorne o id da imagem (não sua URL)
* a função wp_get_attachment_image_src() retorna um array contendo a URL da
* imagem com base no tamanho informado ('medium') na posição 0 e outras
* informações nas próximas posições.
* Se não quisermos puxar um tamanho específico da imagem podemos passar sua
* URL direto em 'src'.
*/
<?php
	$imagem = wp_get_attachment_image_src(get_field('foto_rest_id'), 'medium')[0];
?>
<img src="<?php echo $imagem; ?>" alt="Fachada do Rest">
```

- Os tamanhos das imagens são definidos na UI do Wordpress em Settings/Media.
- Outra forma é por meio da função `add_image_size()`. Ex:
    
    ```php
    function my_custom_sizes() {
    	// name, width, height, crop
    	// no lugar de 'large' podemos usar 'medium' ou 'thumb' para sobreescrever os tamanhos default, ou passar outro nome para criar um tamanho customizado
    	add_image_size('large', 1400, 380, true);
    }
    
    add_action('after_setup_theme', 'my_custom_sizes');
    ```
    

Para usar um campo de repetidor criamos ele da seguinte maneira:

```php
/*Primeiramente criamos um grupo de campos*/
$pratos = $cmb2_home->add_field([
	'name' => 'Comidas',
	'id' => 'pratos',
	'type' => 'group', // deve ser group
	'repeatable' => true, // deve ser true
	'options' => [ // opicional, pode ser usado para alterar a UI do wordpress
		'group_title' => 'Prato {#}' // O título de cada linha, # será substituído pelo número do prato
		'add_button' => 'Adicionar', // label do botão de adicionar
		'remove_button' => 'Remover', // label do botão de remover
		'sortable' => true, // permite o usuário trocar a ordem das linhas
	]
]);

/*Para adicionar um subfield*/
$cmb_home->add_group_field($pratos, [
	/*Aqui passamos os mesmo campos para criar um field*/
])
```

Para usar no template usamos como um array comum no PHP:

```php
<?php
$pratos = get_field('pratos');
if (isset($pratos)) { foreach ($pratos as $prato) { ?>
  <li>
    <span><?php echo $prato['preco']; ?></span>
    <div>
      <h3><?php echo $prato['nome']; ?></h3>
      <p><?php echo $prato['descricao']; ?></p>
    </div>
  </li>
<?php } } ?>
```

## functions.php

```php
<?php

// Funções para Limpar o Header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0 );
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
?>
```

## Adicionar menus

```php
<?php
// em functions.php usamos
add_theme_support('menus');
?>
...
<?php
// Habilitar Menus
// Para acessá-los deve-se acessar na UI do wordpress Aparência/Menus,
// criar um menu com os itens desejados, e em Manage Locations mapear o menu
// definido no código com o menu criado na UI

/* Para adicionar esse menu no site usamos no lugar do código estático:
<?php
	$args = [
		'menu' => 'principal',          // nome que vamos dar para o menu
		'theme_location' => 'header-menu',       // valor passado em register_nav_menu
		'container' => false            // se queremos um container em volta do menu (altera a tagem volta dele)
	];
	wp_nav_menu( $args );
?>
*/
?>
```

Caso exista mais de um menu no site devemos adicionar em functions.php:

- Dentro de `register_nav_menu()` podemos passar um array, ex: `['header_menu' => __('Header Menu')]`

![image.png](Origamid%201c483744f87d8052ada5e8260868e36a/image%203.png)

- Para estilizar o item selecionado, alteramos o seguinte seletor `.current_page_item a`.

## Mudar o estilo CSS a partir de custom fields

Podemos fazer isso usando a tag `style`. Essa tag pode ser adicionada em *pages* ou em arquivos no inc.

## Custom Post Types

Criamos usando a função `register_post_type`. Executamos ela quando a ação `init` ocorrer. Sempre que for adicionado um custom post type devemos ir em Configurações/Links Permanetes e salvar para atualizar todos os links.

Templates para esse tipo de post devem ter seu nome da seguinte forma: ``single-${register}``.

## WP Quey

Faz um loop sobre um post type específico.

```php
$args = [
	'post_type' => 'produtos',
	'order' => 'ASC',
];
$the_query = new WP_Query ($args);
// loop usando $the_query->have_posts() e $the_query->the_post()
```

- dentro do loop podemos usar `the_permalink()` para pegar o link do post.
- Um loop dentro de outro sobrescreve o loop anterior, no fim do loop mais interno não será pego outras informações dentro do loop externo. Para voltar a pegar essas informações é necessário resetar o loop usando: `wp_reset_query(); wp_reset_postdata();`.

## Adicionar javascript e CSS

Devemos registrar os scripts e plugins no site usando a função `wp_register_script()` que ocorre na ação `'wp'_enqueue_scripts'`. Os parâmetros da função são:

- nome do script
- caminho do script
- suas dependências (um array com o nome dos scripts/plugins)
- versão do plugin (caso não tenha, coloque `false`)
- se deve aparecer no footer (coloque `true` se sim)

![image.png](Origamid%201c483744f87d8052ada5e8260868e36a/image%204.png)

Para registrar o CSS fazemos da mesma forma, mas usando a função `wp_register_style()`.

## SEO

- `wp_title()` : podemos passar como argumento um separador, que será usado antes do título da página. Pode ser usado nas meta tags de *description, og:title, og:description* e na tag *title*. Exemplo: `<?php bloginfo('name'); ?> <?php wp_title('-') ?> <?php the_field('title_seo); ?>`
- `bloginfo('url')`: usado para exibir o link do site, pode ser passado em: `<meta property="og:url" content="<?php bloginfo('url') ?>">`

## Blog

Quando substituímos a página inicial, sobrepomos a configuração padrão do wordpress que define como inicial a página com as últimas postagens. por isso devemos criar uma nova página com essa finalidade. Na criação da página não precisamos configurar nada, apenas publicar. Mas devemos definir a página de posts na Configurações/Leitura.

Por padrão será usado como template a página index, mas podemos criar um arquivo específico chamado home.php.

### Novidades

- Nesse template, O Loop deve ficar em volta apenas do conteúdo que queremos repetir.
- Entre o `else` e o `endwhile` devemos adicionar o código referente a páginação, por exemplo:

![image.png](Origamid%201c483744f87d8052ada5e8260868e36a/image%205.png)

### Template para visualizar um post

Dessa vez podemos usar o index.php, ou criar um arquivo single-post.php, outros nomes também são possíveis.

## Performance

- imports no css são considerados como um arquivo diferente, por tanto, é feito uma requisição para cada um desses arquivos na hora de renderizar a página.
    - Uma solução é juntar todos estilos em só um arquivo final.
    - No wordpress essa solução é mais fácil, basta usar o plugin **w3-total-cache**. O que esse plugin faz é gerar arquivos HTML estáticos a partir dos arquivos PHP, além de juntar todo CSS em um arquivo único. Para configurar o plugin devemos ir na nova aba Performance:
        - Usar minify em scripts pode gerar conflito, dependendo do código do site. (alterar em minify)
        - @import handling deve ser setado como Process. (alterar em minify)
        - page cache (alterar em general settings)
        - habilitar minify (alterar em general settings)
        - É sempre bom limpar o cache (em dashboard) quando for adicionado um item novo no site.

## Deploy

Pode ser feito de forma manual (copiar os arquivos, exporta o bd), ou podemos utilizar um plugin como o **Duplicator**. Ao fazer o deploy é interessante desabilitar o w3-total-cache.

O Duplicator cria um pacote do projeto para o estado atual dele. Ao criar um pacote é gerado dois arquivos, um de instalação e outro de com tudo do site compactado (serve como backup). Será necessário subir o installer.php para o servidor e executá-lo, em alguns serviços isso é feito acessando *URL_DO_SITE.com/installer.php*.

Depois de instalar, podemos desativar o duplicator e reativar o w3-total-cache no site final.

## Formulário

Podemos usar o PHPMailer, em vez da biblioteca padrão do PHP. Outras alternativas são:

- **Form contact 7**: mais personalizável

## Conclusão

- Temas: [themeforest.net](http://themeforest.net), [themefuse.com](http://themefuse.com)