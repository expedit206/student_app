import{M as d}from"./MonLayout-C4rYQA64.js";import{d as f,c as p,o,w as g,a as t,b as n,e as a,p as i,t as r,u,q as h,F as _,f as y,z as m}from"./app-CLNz0TeQ.js";import{_ as w}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./DropdownMenuTrigger.vue_vue_type_script_setup_true_lang-Bms3sb-l.js";import"./utils-Nv1ttDiP.js";import"./TextLink.vue_vue_type_script_setup_true_lang-BdFudh-7.js";const b={class:"max-w-5xl mx-auto p-4 sm:p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen"},v={class:"mb-6 flex flex-col sm:flex-row items-center justify-between animate-slide-in"},k={class:"text-2xl sm:text-3xl md:text-4xl font-extrabold text-white flex flex-wrap items-center justify-center sm:justify-start"},D={key:0,class:"ml-2 text-teal-300 text-lg sm:text-xl"},j={class:"text-xs sm:text-sm text-gray-400 mt-2 sm:mt-0"},B={class:"bg-gradient-to-r from-teal-600 to-teal-500 rounded-xl p-4 sm:p-6 shadow-lg mb-6 animate-fade-in"},C={class:"flex flex-col sm:flex-row items-center justify-between gap-4"},M={class:"text-center sm:text-left"},P={class:"text-3xl sm:text-4xl md:text-5xl font-extrabold text-white mt-1 sm:mt-2"},z={class:"w-full sm:w-2/3 md:w-1/2"},F={class:"bg-gray-700 rounded-full h-4 sm:h-6 overflow-hidden shadow-inner"},L={key:0,class:"absolute inset-0 flex items-center justify-center text-white text-xs sm:text-sm font-semibold"},N={class:"bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6 animate-fade-in"},S={key:0,class:"space-y-3"},T={class:"flex items-center w-full sm:w-auto"},V={class:"text-white font-medium text-sm sm:text-base break-words w-full"},E={key:1,class:"text-center text-gray-400 py-6"},q=f({__name:"Progression",props:{progressionData:{}},setup(x){const s=x,c=new Date().toLocaleDateString("fr-FR",{year:"numeric",month:"long",day:"numeric"});return(A,e)=>(o(),p(d,null,{default:g(()=>[t("div",b,[t("header",v,[t("h1",k,[e[0]||(e[0]=t("i",{class:"fas fa-chart-line mr-2 sm:mr-3 text-teal-400 animate-pulse text-xl sm:text-2xl"},null,-1)),e[1]||(e[1]=n(" Ma Progression ")),s.progressionData.formation?(o(),a("span",D," - "+r(s.progressionData.formation.name),1)):i("",!0)]),t("div",j,"Mis à jour le "+r(u(c)),1)]),t("div",B,[t("div",C,[t("div",M,[e[2]||(e[2]=t("p",{class:"text-xs sm:text-sm text-teal-200 uppercase tracking-wide"},"Progression Totale",-1)),t("h2",P,r(s.progressionData.progression)+"% ",1)]),t("div",z,[t("div",F,[t("div",{class:"bg-teal-400 h-full rounded-full transition-all duration-1000 ease-out relative",style:h({width:`${s.progressionData.progression}%`})},[s.progressionData.progression>30?(o(),a("span",L,r(s.progressionData.progression)+"% ",1)):i("",!0)],4)])])])]),t("div",N,[e[4]||(e[4]=t("h3",{class:"text-lg sm:text-xl font-semibold text-white mb-4 flex items-center justify-center sm:justify-start"},[t("i",{class:"fas fa-list-check mr-2 text-teal-400 text-lg"}),n(" Disciplines ")],-1)),s.progressionData.disciplines.length?(o(),a("div",S,[(o(!0),a(_,null,y(s.progressionData.disciplines,l=>(o(),a("div",{key:l.id,class:"p-3 sm:p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all duration-300 flex flex-col sm:flex-row items-center justify-between gap-2"},[t("div",T,[t("i",{class:m([l.completed?"fas fa-check-circle text-green-400":"fas fa-circle text-gray-400","mr-2 sm:mr-3 text-lg flex-shrink-0"])},null,2),t("p",V,r(l.name),1)]),t("span",{class:m(["text-xs sm:text-sm px-2 sm:px-3 py-1 rounded-full font-semibold flex-shrink-0",l.completed?"bg-green-500 text-white":"bg-gray-500 text-gray-200"])},r(l.completed?"Terminé":"En cours"),3)]))),128))])):(o(),a("div",E,e[3]||(e[3]=[t("i",{class:"fas fa-info-circle mr-2 text-xl"},null,-1),n(" Aucune discipline disponible ")])))])])]),_:1}))}}),K=w(q,[["__scopeId","data-v-3c8109f0"]]);export{K as default};
