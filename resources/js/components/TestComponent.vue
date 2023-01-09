<template>
    <div class="row">
        <modal-component v-if="editingCard" @close="resetEditingCard">
            <template #header>
                <h3>Edit card</h3>
            </template>
            <template #body>
                <div>
                    <input v-model="editingCardForm.title" placeholder="Title..." />
                </div>
                <div>
                    <input v-model="editingCardForm.description" placeholder="Description..." />
                </div>
            </template>
            <template #footer>
                <div class="column__action">
                    <button @click="updateCard" class="btn btn__primary">Update</button>
                    <button @click="resetEditingCard" class="btn btn__secondary__outlined">
                        Close
                    </button>
                </div>
            </template>
        </modal-component>

        <div v-for="(column, columnIndex) in columns" :key="`column-${columnIndex}`" class="column">
            <div class="card__header">
                <span class="card__title">
                    {{ column.title }}
                </span>
                <button @click="deleteColumn(column.id)" class="btn__icon mdi mdi-close" />
            </div>
            <v-draggable :list="column.cards" group="cards" @change="MoveCard(column.id, column.cards, $event)">
                <div v-for="(card, cardIndex) in column.cards" :key="`card-${cardIndex}`"
                    @click="editCard(columnIndex, cardIndex)" class="card">
                    <div class="card__text">
                        {{ card.title }}
                    </div>
                    <i class="btn__icon mdi mdi-pencil" />
                </div>
            </v-draggable>
            <div v-if="addingCardColumn && addingCardColumn.id === column.id">
                <input :id="`card-field-${column.id}`" v-model="cardTitleField" class="input" />
                <div class="column__action">
                    <button @click="storeCard" class="btn btn__primary">Add</button>
                    <button @click="resetAddingCard" class="btn btn__secondary__outlined">
                        Close
                    </button>
                </div>
            </div>
            <button v-else @click="createCard(column)" class="btn btn__text">
                <span class="mdi mdi-plus" />
                Add a card
            </button>
        </div>
        <div class="column">
            <div v-if="isAddingColumn">
                <input ref="columnField" v-model="columnTitleField" class="input" />
                <div class="column__action">
                    <button @click="storeColumn" class="btn btn__primary">Add</button>
                    <button @click="resetAddingColumn" class="btn btn__secondary__outlined">
                        Close
                    </button>
                </div>
            </div>
            <button v-else @click="createColumn" class="btn btn__text">
                <span class="mdi mdi-plus" />
                Add a Column
            </button>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import VDraggable from "vuedraggable";
import ModalComponent from "./ModalComponent";

export default {
    components: {
        VDraggable,
        ModalComponent,
    },
    mounted() {
        this.fetchData();
    },
    data() {
        return {
            isLoading: false,
            columnTitleField: '',
            isAddingColumn: false,
            columns: [],
            addingCardColumn: null,
            cardTitleField: '',
            editingCard: null,
            editingCardForm: null,
        }
    },
    methods: {
        fetchData() {
            this.isLoading = true;
            axios.get(`/api/columns`).then((response) => {
                this.columns = response.data;
            }).finally(() => {
                this.isLoading = false;
            })
        },
        MoveCard(columnId, cards, event) {
            if (event.hasOwnProperty('moved') || event.hasOwnProperty('added')) {
                axios.post(`/api/columns/${columnId}/cards/moved`, {
                    cards: cards,
                })
            }
        },

        // Reset All Adds
        resetAddingColumn() {
            this.isAddingColumn = false;
            this.columnTitleField = ''
        },
        resetAddingCard() {
            this.addingCardColumn = null;
            this.cardTitleField = ''
        },
        resetEditingCard() {
            this.editingCard = null;
            this.editingCardForm = null;
        },

        // Create
        createColumn() {
            this.columnTitleField = ''
            this.isAddingColumn = true;
            Vue.nextTick(() => {
                this.$refs.columnField.focus();
            })
        },
        createCard(column) {
            this.cardTitleField = ''
            this.addingCardColumn = column;
            Vue.nextTick(() => {
                document.getElementById(`card-field-${column.id}`).focus();
            })
        },


        // Store
        storeColumn() {
            axios.post(`/api/columns`, {
                title: this.columnTitleField,
                order: this.columns.length,
            }).then((response) => {
                this.columns.push(response.data)
                this.resetAddingColumn();
            }).catch(() => {
                alert('Add column failed');
            })
        },

        storeCard() {
            axios.post(`/api/columns/${this.addingCardColumn.id}/cards`, {
                title: this.cardTitleField,
                order: this.addingCardColumn.cards.length,
            }).then((response) => {
                this.addingCardColumn.cards.push(response.data)
                this.resetAddingCard();
            }).catch(() => {
                alert('Add card failed');
            })
        },


        // Edit
        editCard(columnIndex, cardIndex) {
            this.editingCard = this.columns[columnIndex].cards[cardIndex];
            this.editingCardForm = Object.assign({
            }, this.editingCard);
        },

        // Update
        updateCard() {
            axios.put(`/api/cards/${this.editingCard.id}`, {
                title: this.editingCardForm.title,
                description: this.editingCardForm.description,
            }).then((response) => {
                this.editingCard.title = response.data.title;
                this.editingCard.description = response.data.description;
                this.resetEditingCard();
            }).catch(() => {
                alert('Update card failed');
            })
        },

        // Delete
        deleteColumn(id) {
            axios.delete(`/api/columns/${id}`).then(() => {
                this.columns = this.columns.filter(column => column.id !== id)
            })
        }
    }
}
</script>
