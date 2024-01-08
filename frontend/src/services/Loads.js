import { defineStore } from "pinia";
import apiRequest from "./api";

export const loadStore = defineStore('loads', {
    state: (()=>({
        loads: '',
        response: '',
        summary: '',
    })),
    actions: {
        async read_professor_details(professor){
            const response = await apiRequest.get(`/api/loads/read_professor_details/${professor}`);
            this.response = response;
        },

        async delete_load(id){
            const response = await apiRequest.get(`/api/loads/delete_load/${id}`);
            this.response = response;
        },

        async read_loads(data){
            const formData = new FormData();
            formData.append('year',data.year);
            formData.append('semester',data.semester);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/loads/read_all_load',formData);
            this.response = response;
        },

        async create_load(data){
            const formData = new FormData();
            formData.append('year',data.year);
            formData.append('semester',data.semester);
            formData.append('professor',data.professor);
            formData.append('subject',data.subject);
            formData.append('occupied',data.occupied);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/loads/create_load',formData);
            this.response = response;
        },

        async summary_loads(semester){
            const formData = new FormData();
            formData.append('semester',semester);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/loads/summary',formData);
            this.summary = response;
        },

        async summary_professors(semester){
            const formData = new FormData();
            formData.append('semester',semester);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/loads/professor_summary',formData);
            this.summary = response;
        },
    },
    getters: {
        getLoad(state){
            return state.loads;
        },

        getResponse(state){
            return state.response;
        },

        getSummary(state){
            return state.summary;
        }
    }
})