import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    // Popula o select de estados assim que a página carregar
    populateStates();

    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    const ibgeInput = document.getElementById('ibge_code');
    const stateCodeInput = document.getElementById('state_code');
    const zipcodeInput = document.getElementById('zipcode');
    const streetInput = document.getElementById('street');
    const neighborhoodInput = document.getElementById('neighborhood');

    // Quando o estado é alterado manualmente, atualiza o hidden state_code e popula cidades.
    if (stateSelect) {
        stateSelect.addEventListener('change', async (e) => {
            const selectedOption = stateSelect.options[stateSelect.selectedIndex];
            const uf = stateSelect.value;
            const stateCode = selectedOption ? selectedOption.getAttribute('data-code') : '';
            stateCodeInput.value = stateCode;
            await populateCities(uf);
        });
    }

    // Quando o CEP perde o foco, busca os dados no ViaCEP e atualiza os campos.
    if (zipcodeInput) {
        zipcodeInput.addEventListener('blur', async () => {
            const cep = zipcodeInput.value.replace(/\D/g, '');
            if (cep.length === 8) {
                try {
                    const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = response.data;
                    if (!data.erro) {
                        // Preenche os campos de endereço
                        streetInput.value = data.logradouro || '';
                        neighborhoodInput.value = data.bairro || '';

                        // Atualiza o select de estado e o hidden state_code
                        if (stateSelect) {
                            stateSelect.value = data.uf || '';
                            stateSelect.setAttribute('data-selected', data.uf || '');
                            const selectedOption = stateSelect.options[stateSelect.selectedIndex];
                            if (selectedOption) {
                                stateCodeInput.value = selectedOption.getAttribute('data-code') || '';
                            }
                            // Popula o select de cidades com base no estado
                            await populateCities(data.uf);
                            // Atualiza o select de cidade com o valor do CEP (data.localidade)
                            if (citySelect) {
                                citySelect.value = data.localidade || '';
                                citySelect.setAttribute('data-selected', data.localidade || '');
                            }
                        }

                        // Atualiza o input hidden do IBGE, se disponível
                        if (ibgeInput && data.ibge) {
                            ibgeInput.value = data.ibge;
                        }
                    } else {
                        console.error("CEP não encontrado");
                    }
                } catch (error) {
                    console.error("Erro ao buscar CEP:", error);
                }
            }
        });
    }
});

// Função para popular o select de estados com os dados da API do IBGE
async function populateStates() {
    try {
        const response = await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
        const states = response.data;
        const stateSelect = document.getElementById('state');
        if (stateSelect) {
            const selectedState = stateSelect.getAttribute('data-selected') || '';
            stateSelect.innerHTML = '<option value="">Selecionar</option>';
            states.forEach(state => {
                stateSelect.innerHTML += `<option value="${state.sigla}" data-code="${state.id}">${state.nome}</option>`;
            });
            if (selectedState) {
                stateSelect.value = selectedState;
            }
        }
    } catch (error) {
        console.error("Erro ao buscar estados:", error);
    }
}

// Função para popular o select de cidades com base no estado (UF)
async function populateCities(uf) {
    try {
        const response = await axios.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`);
        const cities = response.data;
        const citySelect = document.getElementById('city');
        if (citySelect) {
            const selectedCity = citySelect.getAttribute('data-selected') || '';
            citySelect.innerHTML = '<option value="">Selecionar</option>';
            cities.forEach(city => {
                citySelect.innerHTML += `<option value="${city.nome}">${city.nome}</option>`;
            });
            if (selectedCity) {
                citySelect.value = selectedCity;
            }
        }
    } catch (error) {
        console.error("Erro ao buscar cidades:", error);
    }
}
