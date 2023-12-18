# Módulo Magento 2: Color Change

Este módulo Magento 2 permite alterar dinamicamente a cor dos botões em uma loja Magento 2 usando um comando de console.

*USO*
Execute o seguinte comando de console para alterar a cor dos botões em uma store view específica:

bin/magento color:change HEX_COLOR STORE_ID

Substitua HEX_COLOR pelo código hexadecimal da nova cor desejada e STORE_ID pelo ID da store view.

## Funcionamento Interno
*ColorChangeCommand.php*
1. Este arquivo é responsável por receber os parâmetros do console e modificar a cor dos botões.
2. Ele carrega o bloco CMS identificado como button_block e salva a nova cor em um arquivo XML de configuração.
*BlockPlugin.php*
1. Este plugin é aplicado ao bloco Magento\Cms\Block\Block e adiciona o atributo button_color ao bloco antes da renderização.
Obtém a cor do arquivo de configuração com base no ID da store.
*cms_block_view.xml*
Este arquivo de layout XML injeta a cor do botão no bloco CMS button_block.
*Configuração*
O arquivo XML de configuração está localizado em var/color_change_config.xml. Cada loja (store) possui uma entrada no arquivo para armazenar a cor do botão.

Exemplo de Estrutura do Arquivo de Configuração:

<config>
    <store id="1">
        <color>#FF0000</color>
    </store>
    <!-- Outras lojas aqui -->
</config>
