<template>
    <form action="/api-test" class="flex flex-col">
        <div class="flex gap-4 w-full">
            <textarea v-model="question" class="rounded-xl border-gray-400 w-full" id="question" name="question"
                placeholder="Ask your question here." rows="6"></textarea>
            <div class="w-full flex flex-col items-center">
                <textarea v-model="response" class="rounded-xl border-gray-400 w-full" id="reply" name="reply" disabled
                    placeholder="Answer will appear here." rows="6"></textarea>
                <div class="flex flex-col items-center" v-if="response !== ''">
                    <label for="rating">Rate the answer:</label>
                    <div>
                        <input class="hidden" type="radio" id="star1" name="rating" value="1" v-model="rating">
                        <label for="star1">&starf;</label>
                        <input class="hidden" type="radio" id="star2" name="rating" value="2" v-model="rating">
                        <label for="star2">&starf;</label>
                        <input class="hidden" type="radio" id="star3" name="rating" value="3" v-model="rating">
                        <label for="star3">&starf;</label>
                        <input class="hidden" type="radio" id="star4" name="rating" value="4" v-model="rating">
                        <label for="star4">&starf;</label>
                        <input class="hidden" type="radio" id="star5" name="rating" value="5" v-model="rating">
                        <label for="star5">&starf;</label>
                    </div>
                </div>
            </div>
        </div>
        <button @click.prevent="askQuestion()"
            class="mt-4 border border-solid border-2 border-slate-500 text-slate-700 bg-slate-200 p-2 rounded-md"
            type="submit">Ask
            Question</button>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            question: '',
            response: '',
            rating: null,
            chatId: null,
        }
    },

    methods: {
        askQuestion() {
            this.response = ''
            axios.post('/ask-question', { question: this.question })
                .then(response => {
                    this.response = response.data.response;
                    this.chatId = response.data.chatId;
                    this.rating = null
                })
                .catch(error => {
                    console.error('There was an error!', error);
                });
        }
    },

    watch: {
        rating(newRating, oldRating) {
            axios.post('/update-rating', { chat: this.chatId, rating: this.rating })
        }
    }
}
</script>
