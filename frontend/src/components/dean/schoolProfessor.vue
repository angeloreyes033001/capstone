<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Professor</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <button :disabled="isProcess || isEditMode !=''" class="btn btn-outline-primary w-25" data-bs-toggle="modal" data-bs-target="#addModal" >
                    <fa icon="plus" ></fa>
                    ADD PROFESSOR
                </button>
            </div>
            <div class="table-holder mt-3" >
            <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                <div class="t-flex t-justify-between t-mb-2 t-pb-2 t-border t-border-t-slate-100 t-border-l-slate-100 t-border-r-slate-100 t-border-b-slate-300" >
                    <div class="t-flex t-gap-1" >
                        <button @click="change_tab('list')" :disabled="isProcess || isEditMode !=''" class="btn t-w-[150px]" :class="{'btn-primary': isSelectedTab ==='list'}" >
                            <fa icon="list" ></fa>
                            &nbsp;
                            List
                        </button>
                        <button @click="change_tab('assigned')" :disabled="isProcess || isEditMode !=''" class="btn t-w-[150px]" :class="{'btn-primary': isSelectedTab ==='assigned'}" >
                            <fa icon="check" ></fa>
                            &nbsp;
                            Assign
                        </button>
                    </div>
                    <div >
                        <input v-model="isSearch" :disabled="isProcess || isEditMode !='' " v-if="isSelectedTab == 'list'"  class="form-control t-capitalize" placeholder="Search" type="text" >
                    </div>
                </div>
                <div>
                    <div v-show="isSelectedTab === 'list'" >
                        <!-- list div -->
                        <div class="t-inline-block" >
                            <div v-if="professorData.length > 0" v-for="professor in computed_professor" class="t-bg-white t-w-[300px] t-shadow t-h-auto t-inline-block t-m-1" >
                                <div class="t-grid t-rounded-[10px] parent-white t-w-full" >
                                    <div  class="t-bg-logoBlue t-p-2 t-rounded-[10px] t-grid t-grid-cols-[80px,1fr] t-w-full" >
                                        <div class="t-flex t-justify-center t-items-center t-p-2" >
                                            <img src="../../assets/images/profile.png" class="t-bg-white t-rounded-[50%]" >
                                        </div>
                                        <div class="t-flex t-items-end t-overflow-hidden t-w-full" >
                                            <span class="t-grid t-w-full" >
                                                <div class="t-w-full" >
                                                    <small v-if="isEditMode != professor.professorID" :title="professor.fullname" class="t-font-bold t-text-white t-capitalize t-text-[20px] t-truncate" >{{professor.fullname}}</small>
                                                    <div v-else class="form-group" >
                                                        <input v-model="professors.fullname" class="form-control text-capitalize" >
                                                    </div>
                                                </div>
                                                <small class="t-font-extralight t-text-white t-lowercase" >{{ professor.professorID }}</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="t-bg-white t-p-2 t-rounded-[10px] child-white t-w-full" >
                                        <span class="t-grid t-grid-cols-[100px,1fr] t-p-1" >
                                            <div class="t-flex t-items-center" >
                                                <small class="t-font-[14px] text-muted" >Status</small>
                                            </div>
                                            <div class="t-flex t-items-center" >
                                                <small v-if="isEditMode != professor.professorID" class="t-font-[14px] text-muted t-capitalize" >{{professor.status}}</small>
                                                <div v-else class="form-group t-w-full" >
                                                    <select v-model="professors.status" class="form-select t-w-full" >
                                                        <option value="regular">Regular</option>
                                                        <option value="part-time">Part-Time</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="t-grid t-grid-cols-[100px,1fr] t-p-1" >
                                            <div class="t-flex t-items-center" >
                                                <small class="t-font-[14px] text-muted" >Rank</small>
                                            </div>
                                            <div class="t-flex t-items-center" >
                                                <small v-if="isEditMode != professor.professorID" class="t-font-[14px] text-muted t-capitalize" >{{professor.rank}}</small>
                                                <div v-else class="form-group t-w-full" >
                                                    <select v-model="professors.rank" class="form-select t-w-full t-capitalize" >
                                                        <option class="t-capitalize" v-for="rank in globalRankData" :value="rank.id">{{rank.rank}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="t-grid t-grid-cols-[100px,1fr] t-p-1" >
                                            <div class="t-flex t-items-center" >
                                                <small class="t-font-[14px] text-muted" >Designated</small>
                                            </div>
                                            <div class="t-flex t-items-center" >
                                                <small v-if="isEditMode != professor.professorID" class="t-font-[14px] text-muted t-capitalize" >{{professor.designation}}</small>
                                                <div v-else class="form-group t-w-full" >
                                                    <select v-model="professors.designation" class="form-select t-w-full t-capitalize" >
                                                        <option value="none" >None</option>
                                                        <option v-for="designated in globalDesignationData" class="t-capitalize" :value="designated.position">{{designated.position}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </span>
                                        <div class="t-grid t-grid-cols-2 t-gap-1 t-mt-1 t-p-1" >
                                            <button  @click="getUpdateData(
                                                {
                                                    professorID: professor.professorID,
                                                    fullname: professor.fullname,
                                                    status: professor.status,
                                                    rank: professor.rankID,
                                                    designation: professor.designation
                                                }
                                                )" :disabled="isProcess " class="btn btn-outline-secondary" >
                                                <fa icon="edit" ></fa>
                                            </button>
                                            <button v-if="isEditMode == ''" @click="delete_professor(professor.professorID)" :disabled="isProcess" class="btn btn-outline-danger" >
                                                <fa icon="trash" ></fa>
                                            </button>
                                            <button v-else @click="update_professor" :disabled="isProcess" class="btn btn-outline-primary" >
                                                <fa icon="save" ></fa>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                                <img class="t-w-[700px]" src="../../assets/images/no-data.svg" alt="no-data">
                                <h6 class="text-center t-capitalize t-mt-2" > no record found</h6>
                            </div>
                        </div>
                    </div>
                    <!-- assigned -->
                    <div  v-show="isSelectedTab === 'assigned'" class="t-w-full" >
                        <!-- <div class="" >
                            <div class="t-mb-3" >
                                <div class="" >
                                    <div>
                                        <input class="form-control" placeholder="Search" v-model="isSearchAssigned" >
                                    </div>
                                </div>
                            </div>
                            <div v-for="professor in computed_read_load_professorData" :class="{'t-bg-slate-300/50 t-shadow': isOpenLoad === professor.info.professorID, 't-bg-white': isOpenLoad != professor.info.professorID}" class=" t-rounded-[10px] t-p-5 t-mt-5 t-mb-5" >
                                <div class="t-grid t-grid-cols-[100px,1fr]" >
                                    <div class="t-p-2" >
                                        <img src="../../assets/images/profile.png" >
                                    </div>
                                    <div class="t-flex t-items-center" >
                                        <div>
                                            <div class="t-grid t-grid-cols-[130px,1fr]" >
                                                <span class="t-text-[14px]" >Professor:</span>
                                                <span class="t-capitalize t-text-[14px]" >{{professor.info.fullname}}</span>
                                            </div>
                                            <div class="t-grid t-grid-cols-[130px,1fr]" >
                                                <span class="t-text-[14px]" >Rank:</span>
                                                <span class="t-capitalize t-text-[14px]" >{{professor.info.rank}}</span>
                                            </div>
                                            <div class="t-grid t-grid-cols-[130px,1fr]" >
                                                <span class="t-text-[14px]" >Designated:</span>
                                                <span class="t-capitalize t-text-[14px]" >{{professor.info.designated}}</span>
                                            </div>
                                            <div class="t-grid t-grid-cols-[130px,1fr]" >
                                                <span class="t-text-[14px]" >Hour's:</span>
                                                <span class="t-capitalize t-text-[14px]" >{{professor.info.hour}}</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <hr>
                                <div class="t-bg-white t-p-5 t-rounded-[10px]" >
                                    <h6 class="t-font-bold text-muted" >LOAD</h6>
                                    <div class="table_holder " >
                                        <div class="t-h-[40px] t-bg-slate-200 t-grid t-grid-cols-3" >
                                            <span class="t-flex t-items-center t-pl-[10px] t-font-bold" >Subject</span>
                                            <span class="t-flex t-items-center t-pl-[10px] t-font-bold" >Hour</span>
                                            <span class="t-flex t-items-center t-pl-[10px] t-font-bold" >Action</span>
                                        </div>
                                        <div v-if="professor.loads.length > 0" v-for="load in professor.loads" class="t-h-[50px] t-grid t-grid-cols-3" >
                                            <span class="t-flex t-items-center t-pl-[10px] t-font-extralight" >{{ load.subject }}</span>
                                            <span class="t-flex t-items-center t-pl-[10px] t-font-extralight" >{{ load.hour }}</span>
                                            <span class="t-flex t-items-center t-pl-[10px] t-font-extralight" >
                                                <button @click="delete_load(load.id)" class="btn btn-danger" >
                                                    <fa icon="trash" ></fa>
                                                </button>
                                            </span>
                                        </div>
                                        <div v-else class="t-h-[50px]" >
                                            <div class="t-flex t-h-full t-items-center t-justify-center t-pl-[10px] t-font-extralight" >
                                                <b>NO LOAD RECORD</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="" >
                                    <div class="t-flex t-justify-end" >
                                        <button @click="selectLoadProfessor(professor.info.professorID)"  class="btn btn-primary w-25" >
                                            <fa icon="gears" ></fa>
                                            Assign Load
                                        </button>
                                    </div>
                                    <div v-show="isOpenLoad == professor.info.professorID" class=" t-mt-2 t-bg-white t-p-5 t-rounded-[10px]" >
                                        <div class="t-flex t-justify-between t-mb-2" >
                                            <div class="form-group" >
                                                <input class="form-control" placeholder="Search Subject" >
                                            </div>
                                            <div class="t-flex t-gap-2" >
                                                <div class="form-group" >
                                                    <select @change="read_all_load" v-model="loads.year" :disabled="isProcess" class="form-select" >
                                                        <option value="" selected disabled >Select Year</option>
                                                        <option class="t-capitalize" v-for="year in globalYearLevelData" :value="year.id" >{{ year.year }}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" >
                                                    <select  @change="read_all_load" v-model="loads.semester" :disabled="isProcess" class="form-select" >
                                                        <option value="1st semester" >1st Semester</option>
                                                        <option value="2nd semester" >2nd Semester</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div classroom="table_holder" >
                                            <div v-for="info in computed_all_loadData" >
                                                <h6 class="t-font-bold text-muted t-capitalize" >{{info.subject}}(<span class="t-uppercase t-font-extralight" >{{ info.code }}</span>)</h6>
                                                <div class="t-grid t-grid-cols-2 t-gap-2" >
                                                    <div>
                                                        <div class="t-flex t-justify-end" >
                                                            <form @submit.prevent="create_load(info.code)" class="t-flex t-gap-2 t-mt-2 t-mb-2"  v-if="info.subjectCurrentHour != info.subjectMaxHour" >
                                                                <div class="form-group" >
                                                                    <input v-model="loads.hour" class="form-control" placeholder="Hour" min="0" max="30" type="number" >
                                                                </div>
                                                                <button type="submit" class="btn btn-primary" >
                                                                    <fa icon="save" ></fa>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="t-bg-logoBlue t-h-[50px] t-grid t-grid-cols-2" >
                                                            <div class="t-flex t-items-center t-h-full t-pl-[10px] t-text-white" >
                                                                Fullname
                                                            </div>
                                                            <div class="t-flex t-items-center t-h-full t-pl-[10px] t-text-white" >
                                                                Hour
                                                            </div>
                                                        </div>
                                                        <div v-for="load in info.loads" class="t-h-[50px] t-grid t-grid-cols-2 t-bg-slate-100" >
                                                            <div class="t-flex t-items-center t-h-full t-pl-[10px]" >
                                                                <h6 class="t-capitalize" >{{load.fullname}}</h6>
                                                            </div>
                                                            <div class="t-flex t-items-center t-h-full t-pl-[10px]" >
                                                                <h6 class="" >{{load.hour}}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="t-grid t-grid-cols-2" >
                                                        <div>
                                                            <div class="t-flex t-items-center t-justify-center" >
                                                                <div class=" t-rounded-[50%] t-p-5" :class="{'t-bg-red-300': info.professorCurrentHour < info.professorMaxHour,'t-bg-green-300': info.professorCurrentHour >= info.professorMaxHour}" >
                                                                    <div class="t-h-[120px] t-p-5 t-w-[120px] t-flex t-justify-center t-items-center t-rounded-[50%]" :class="{'t-bg-red-200': info.professorCurrentHour < info.professorMaxHour,'t-bg-green-200': info.professorCurrentHour >= info.professorMaxHour}" >
                                                                        <div class="t-flex t-justify-center t-items-center t-w-full t-h-full t-rounded-[50%] " :class="{'t-bg-red-100': info.professorCurrentHour < info.professorMaxHour,'t-bg-green-100': info.professorCurrentHour >= info.professorMaxHour}" >
                                                                            <span class="t-font-bolder t-text-[25px] t-text-black/50" >{{info.professorCurrentHour}}/<b>{{ info.professorMaxHour }}</b></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="t-text-center t-text-extralight t-text-[14px] t-mt-3" >Professor Hour's</h6>
                                                        </div>
                                                        <div>
                                                            <div class="t-flex t-items-center t-justify-center" >
                                                                <div class=" t-rounded-[50%] t-p-5" :class="{'t-bg-red-300': info.subjectCurrentHour < info.subjectMaxHour,'t-bg-green-300': info.subjectCurrentHour >= info.subjectMaxHour}" >
                                                                    <div class="t-h-[120px] t-p-5 t-w-[120px] t-flex t-justify-center t-items-center t-rounded-[50%]" :class="{'t-bg-red-200': info.subjectCurrentHour < info.subjectMaxHour,'t-bg-green-200': info.subjectCurrentHour >= info.subjectMaxHour}" >
                                                                        <div class="t-flex t-justify-center t-items-center t-w-full t-h-full t-rounded-[50%] " :class="{'t-bg-red-100': info.subjectCurrentHour < info.subjectMaxHour,'t-bg-green-100': info.subjectCurrentHour >= info.subjectMaxHour}" >
                                                                            <span class="t-font-bolder t-text-[25px] t-text-black/50" >{{info.subjectCurrentHour}}/<b>{{ info.subjectMaxHour }}</b></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="t-text-center t-text-extralight t-text-[14px] t-mt-3" >Subject Hour's</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="" >
                            <div class="t-flex t-justify-between" >
                                <div class="t-flex t-gap-3" >
                                    <select @change="read_loads" v-model="loads.year" class="form-select" >
                                        <option value="" selected disabled >Select Year</option>
                                        <option v-for="year in globalYearLevelData" :value="year.id" >{{ year.year }}</option>
                                    </select>
                                    <select @change="read_loads" v-model="loads.semester" class="form-select" >
                                        <option  value="1st semester" >1st Semester</option>
                                        <option  value="2nd semester" >2nd Semester</option>
                                    </select>
                                </div>
                                <div class="t-flex t-gap-2" >
                                    <button @click="professor_summary" class="btn btn-outline-success"  data-bs-toggle="modal" data-bs-target="#professor_summary_modal" >
                                        <fa icon="users" ></fa>
                                        Professor
                                    </button>
                                    <button @click="summary" class="btn btn-outline-success"  data-bs-toggle="modal" data-bs-target="#summary_modal" >
                                        <fa icon="scroll" ></fa>
                                        Summary
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="t-relative" >
                                <div class="t-flex" >
                                    <input @click="function_profesor_modal" class="form-control" placeholder="Search" >
                                </div>
                                <div v-show="professorDetail.professorID != ''" class="t-mt-2" >
                                    <h6 class="t-m-0 t-p-0 t-uppercase" >Employee ID: {{ professorDetail.professorID }}</h6>
                                    <h6 class="t-m-0 t-p-0 t-capitalize" >Fullname: {{ professorDetail.fullname }}</h6>
                                    <h6 class="t-m-0 t-p-0 t-capitalize" >Rank: {{ professorDetail.rank }}</h6>
                                    <h6 class="t-m-0 t-p-0 t-capitalize" >Designated: {{ professorDetail.designated }}</h6>
                                    <h6 class="t-m-0 t-p-0 t-capitalize" >Hour: {{ professorDetail.hour }}</h6>
                                </div>
                                <div v-show="load_professor_modal" class="t-absolute t-top-[40px] t-z-10 t-bg-slate-300 mt-2 t-h-[300px] t-rounded-[10px] t-w-full t-p-2 t-overflow-auto t-shadow" >
                                    <div class="t-flex t-justify-end" >
                                        <button class="btn btn-danger" @click="function_profesor_modal" >
                                            <fa icon="close" ></fa>
                                        </button>
                                    </div>
                                    <div v-for="professor in computed_professor" @click="selected_professor(professor.professorID)" class="t-cursor-pointer t-bg-white t-p-2 t-mt-2 t-mb-2 t-rounded-[10px]" >
                                        <h6 class="t-p-0 t-m-0 t-uppercase" >{{ professor.fullname }}</h6>
                                        <small class="t-uppercase" >{{ professor.professorID }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="t-mt-3" >
                                <div v-for="subject in loadData" class="t-mt-2 t-mb-2 t-bg-white t-rounded-[10px] p-2" >
                                    <div class="t-h-[40px] t-flex t-justify-between" >
                                        <div class="t-flex t-items-center" >
                                            <h6 class="t-m-0 t-p-0 t-uppercase" >{{subject.subject}}({{ subject.code }})</h6>
                                        </div>
                                        <div class="t-relative" >
                                            <button @click="selected_subject(subject.code)" :disabled="isProcess || loads.professor == '' " class="btn btn-outline-success" >
                                                <span v-if="loads.subject === subject.code" >
                                                    <fa icon="minus" ></fa>
                                                </span>
                                                <span v-else>
                                                    <fa icon="plus" ></fa>
                                                </span>
                                            </button>
                                            <div v-show="loads.subject === subject.code" class="t-absolute t-mt-2 t-w-[200px] t-left-[-160px] t-p-2 t-bg-slate-300 t-flex t-gap-3" >
                                                <select v-model="loads.occupied" class="form-select" >
                                                    <option value="0" >0</option>
                                                    <option v-for="(totalSection,index) in subject.open_section" :value="index+1" >{{ index+1 }}</option>
                                                </select>
                                                <button @click="create_load" :disabled="isProcess ||loads.occupied == 0" class="btn btn-primary" >
                                                    <fa icon="save" ></fa>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="" >
                                        <span class="" >Occupied: {{subject.occupied}}/{{ subject.open_section }}</span>
                                        <div>
                                            <table class="table" >
                                                <thead>
                                                    <tr>
                                                        <th>Professor</th>
                                                        <th>Section</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="load in subject.loads" >
                                                        <td>{{ load.professor }}</td>
                                                        <td>{{ load.section }}</td>
                                                        <td>
                                                            <button @click="delete_load(load.id)" class="btn btn-outline-danger" >
                                                                <fa icon="trash" ></fa>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
     <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="addModalLabel">ADD</h5>
                    <button @click="entireReset()" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="create_professor"  >
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">ID</label>
                                <input v-model="professors.professorID" :disabled="isProcess" type="text" class="form-control text-lowercase" >
                            </div>
                            <small class="text-danger" v-if="professorError.professorID != ''" >{{ professorError.professorID }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Fullname</label>
                                <input v-model="professors.fullname" :disabled="isProcess" type="text" class="form-control text-capitalize" >
                            </div>
                            <small class="text-danger" v-if="professorError.fullname != ''" >{{ professorError.fullname }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select v-model="professors.status" class="form-select" >
                                    <option value="regular" >Regular</option>
                                    <option value="part-time" >Part-Time</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="professorError.status != ''" >{{ professorError.status }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Rank</label>
                                <select v-model="professors.rank" class="form-select t-capitalize" >
                                    <option value="" >Select Rank</option>
                                    <option v-for="rank in globalRankData"  :value="rank.id" class="t-capitalize" >{{ rank.rank }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="professorError.rank != ''" >{{ professorError.rank }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Designation</label>
                                <select v-model="professors.designation" class="form-select t-capitalize" >
                                    <option value="none" >None</option>
                                    <option v-for="designated in globalDesignationData" class="t-capitalize" :value="designated.position">{{designated.position}}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="professorError.designation != ''" >{{ professorError.designation }}</small>
                        </div>
                        <div class="t-flex t-justify-end mt-2" >
                            <button class="btn btn-primary" >
                                <fa icon="save" ></fa>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="summary_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="summary_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="summary_modalLabel">Summary</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="t-flex t-justify-end" >
                <div class="form-group" >
                    <select @change="summary" v-model="summary_semester" class="form-select" >
                        <option value="1st semester" >1st Semester</option>
                        <option value="2nd semester" >2nd Semester</option>
                    </select>
                </div>
            </div>
            <div v-for="summary in summaryData" class="" >
                <hr>
                <h5 class="t-capitalize" >{{ summary.year }}</h5>
                <hr>
                <table class="table" >
                    <thead>
                        <tr>
                            <th width="10%" >Subject</th>
                            <th width="5%" >Total Section</th>
                            <th width="5%" >Total Hours</th>
                            <th width="80%" >Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="subject in summary.subjects" > 
                            <td>{{ subject.code }}</td>
                            <td>{{ subject.total_section }}</td>
                            <td>{{ subject.subject_hour }}</td>
                            <td>
                                <span v-for="name in subject.namelist" class="t-capitalize" >
                                {{ name }},&nbsp;
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="professor_summary_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="professor_summary_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="professor_summary_modalLabel">Summary</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="t-flex t-justify-end" >
                <div class="form-group" >
                    <select @change="professor_summary" v-model="professor_summary_semester" class="form-select" >
                        <option value="1st semester" >1st Semester</option>
                        <option value="2nd semester" >2nd Semester</option>
                    </select>
                </div>
            </div>
            <div class="t-bg-blue-300 t-w-full t-h-[50px] t-grid t-grid-cols-[200px,1fr,100px] t-mt-2 t-px-2 t-rounded-tl-[10px] t-rounded-tr-[10px]" >
                <span class="t-flex t-items-center" >Name</span>
                <span class="t-flex t-items-center" >Subject(section) | Hours</span>
                <span class="t-flex t-items-center" >Total Hours</span>
            </div>
            <div v-for="summary in professor_summaryData" class="odd:t-bg-slate-100 even:t-bg-slate-200" >
                <div class="" >
                    <div class="t-h-[50px] t-grid t-grid-cols-[200px,1fr,100px] t-px-2" >
                        <div class="t-flex t-items-center" >
                            <small class="t-capitalize" >{{ summary.professor }}</small>
                        </div>
                        <div class="t-flex t-items-center" >
                            <div v-for="subject in summary.subjects" >
                                <div class="t-flex t-mx-2 t-p-1" >
                                    <div class="t-mr-3" >
                                        <span class="text-uppercase" >
                                            {{subject.code}}({{subject.givenSection}})
                                        </span>
                                        <span class="t-font-extralight text-muted" >|&nbsp;</span>
                                        <span>
                                            {{ subject.loadHour }}
                                        </span>,
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="t-flex t-items-center" >
                            <div>{{ summary.total_hour }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</template>
<script setup >
import { ref, defineAsyncComponent , computed, inject } from 'vue';
import Swal from 'sweetalert2';
import { professorStore } from '../../services/professors';
import { loadStore } from '../../services/Loads.js';

const use_professorStore = professorStore();
const globalProfessorData = inject("globalProfessorData");
const globalRankData = inject('globalRankData');
const professorData = ref(globalProfessorData);
const globalYearLevelData = inject('globalYearLevelData');

const globalSubjectData = inject('globalSubjectData');
const globalDesignationData = inject('globalDesignationData');

const computed_professor = computed(()=>{
    if(isSearch.value != ""){
        return professorData.value.filter(professor=>{
            return professor.fullname.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return professorData.value;
    }
})

const isProcess = ref(false);
const isSearch = ref('');

const isSearchAssigned = ref('');

const isSelectedTab = ref('list');
const change_tab = async (tab) =>{
    isSelectedTab.value = tab;
}

const professors = ref({
    professorID: '',
    fullname: '',
    status: 'regular',
    rank: '',
    designation: 'none',
})

const professorError = ref({
    professorID: '',
    fullname: '',
    status: '',
    rank: '',
    designation: '',
})

const entireReset = ()=>{
    reset();
    resetError();
}

const reset = ()=>{
    professors.value = {
    professorID: '',
        fullname: '',
        status: 'regular',
        rank: '',
        designation: 'none',
    }
}

const resetError = ()=>{
    professorError.value = {
        professorID: '',
        fullname: '',
        status: '',
        rank: '',
        designation: '',
    }
}

const isEditMode = ref('');
const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.professorID) {
            professors.value = { ...data };
            isEditMode.value = data.professorID;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        professors.value = { ...data };
        isEditMode.value = data.professorID;
    }
}

const create_professor = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then( async (result) => {
            if (result.isConfirmed) {

                isProcess.value = true;

                resetError();
                await use_professorStore.create({...professors.value});
                const response = use_professorStore.getResponse;
                if(response.status){
                    await use_professorStore.read();
                    professorData.value = use_professorStore.getProfessor;
                    Swal.fire("Success",response.msg,'success');
                    entireReset();
                }
                else{
                    if(response.error == 'id'){
                        professorError.value.professorID = response.msg;
                    }

                    if(response.error == 'fullname'){
                        professorError.value.fullname = response.msg;
                    }

                    if(response.error == 'status'){
                        professorError.value.status = response.msg;
                    }

                    if(response.error == 'rank'){
                        professorError.value.rank = response.msg;
                    }

                    if(response.error == 'designation'){
                        professorError.value.designation = response.msg;
                    }
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error)
    }
}

const update_professor = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Update it!"
        }).then( async (result) => {
            if (result.isConfirmed) {

                isProcess.value = true;
                await use_professorStore.update({...professors.value});
                const response = use_professorStore.getResponse;
                if(response.status){
                    await use_professorStore.read();
                    professorData.value = use_professorStore.getProfessor;
                    Swal.fire('Success',response.msg,'success')
                    isEditMode.value = '';
                    reset();
                }else{
                    Swal.fire("Error",response.msg,'error');
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const delete_professor = (id)=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Delete it!"
        }).then( async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_professorStore.delete(id);
                const response = use_professorStore.getResponse;
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    await use_professorStore.read();
                    professorData.value = use_professorStore.getProfessor;
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error);
    }
}


// loaddddddddd --------------------------------------

const use_loadStore = loadStore();
const loads = ref({
    year: '',
    semester: '1st semester',
    professor: '',
    subject: '',
    occupied: 0,
});

const load_professor_modal = ref(false);
const function_profesor_modal = ()=>{
    if(load_professor_modal.value){
        load_professor_modal.value = false;
    }
    else{
        load_professor_modal.value = true;
    }
}

const professorDetail =ref({
    professorID: '',
    fullname: '',
    rank: '',
    designated: '',
    hour: 0
})
const read_professor_details = async()=>{
    try {
        await use_loadStore.read_professor_details(loads.value.professor);
        const response = use_loadStore.getResponse;
        professorDetail.value = {...response}
        console.log(professorDetail.value)
    } catch (error) {
        console.error(error);
    }
}

const selected_professor = (id)=>{
    loads.value.professor = id;
    read_professor_details(id);
    load_professor_modal.value = false;
}

const selected_subject=(code)=>{
    if(loads.value.subject === code){
        loads.value.subject = '';
    }
    else{
        loads.value.subject = code;
    }
}

const create_load = (subject)=>{
    try{
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then(async(result) => {
             await use_loadStore.create_load({...loads.value});

             const response = use_loadStore.getResponse;
             if(response.status){
                loads.value.hour = 0;
                Swal.fire('Success',response.msg,"success");
                read_loads();
             }
             else{
                Swal.fire("Error",response.msg,"error");
             }


        });
    }
    catch(error){
        console.log(error);
    }
}

const loadData = ref([]);
const read_loads = async()=>{
    try {
        isProcess.value = true;

        await use_loadStore.read_loads({year: loads.value.year, semester: loads.value.semester});
        loadData.value = use_loadStore.getResponse;

        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}

const delete_load = async(id)=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Delete it!"
        }).then(async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_loadStore.delete_load(id)
                const response = use_loadStore.getResponse;
                if(response.status){
                    read_loads()
                    Swal.fire("Success",response.msg,"success")
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const summaryData = ref([]);
const summary_semester = ref("1st semester");
const summary = async ()=>{
    try {
        isProcess.value = true;
        await use_loadStore.summary_loads(summary_semester.value);
        const response = use_loadStore.getSummary;
        summaryData.value = response;
        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}

const professor_summaryData = ref([]);
const professor_summary_semester = ref("1st semester");
const professor_summary = async ()=>{
    try {
        isProcess.value = true;
        await use_loadStore.summary_professors(professor_summary_semester.value);
        const response = use_loadStore.getSummary;
        professor_summaryData.value = response;
        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}

</script>
<style scoped>

.parent-white .child-white{
    display: none;
}

.parent-white:hover .child-white{
    display: grid;
    transition: 3s;
}

</style>