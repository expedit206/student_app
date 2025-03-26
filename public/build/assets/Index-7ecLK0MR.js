import{d as w,j as g,q as k,g as u,o as c,e as d,u as C,m as D,w as m,b as e,f as o,h as i,l as F,v as H,i as L,F as y,r as S,n as V,t as x,C as j}from"./app-hzVHw_t7.js";import{_ as p}from"./TextLink.vue_vue_type_script_setup_true_lang-BN_Qr0I0.js";import{M as A}from"./MonLayout-B5dsuzyH.js";import{_ as I}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./DropdownMenuTrigger.vue_vue_type_script_setup_true_lang-B9Uxm6xL.js";import"./utils-EBsy1T7r.js";const M={class:"container mx-auto px-4 py-8 text-gray-900 dark:text-gray-100"},N={class:"flex flex-col sm:flex-row items-center justify-between mb-6 animate-fade-in"},B={class:"text-2xl sm:text-3xl font-extrabold flex items-center text-primary"},$={class:"flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-4 sm:mt-0"},q={class:"relative w-full sm:w-64 animate-slide-up",style:{animationDelay:"0.2s"}},z={class:"grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[70vh] overflow-y-auto animate-fade-in bg-gray-100 dark:bg-gray-900 p-4 rounded-xl",style:{animationDelay:"0.4s"}},T={class:"flex flex-col space-y-3"},E={class:"flex items-center justify-between"},R=["title"],U={class:"text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full"},G=["title"],J={class:"flex flex-wrap gap-3 pt-2 border-t border-gray-300 dark:border-gray-600"},K=["onClick"],O={key:0,class:"col-span-full p-6 text-center text-gray-500 dark:text-gray-400 animate-pulse bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700"},P=w({__name:"Index",props:{formations:{type:Array,required:!0}},setup(v){const f=v,n=g(""),l=g([...f.formations]);k(n,()=>{b()});function b(){n.value?l.value=f.formations.filter(a=>a.titre.toLowerCase().includes(n.value.toLowerCase())):l.value=[...f.formations]}function h(a){confirm("Êtes-vous sûr de vouloir supprimer cette formation ?")&&j().delete(route("formations.destroy",a),{preserveScroll:!0,onSuccess:()=>{l.value=l.value.filter(t=>t.id!==a)}})}const r={base:"text-blue-500",hover:"text-blue-400",edit:"text-yellow-500",editHover:"text-yellow-400",delete:"text-red-500",deleteHover:"text-red-400",manage:"text-purple-500",manageHover:"text-purple-400"};return(a,t)=>(c(),u(y,null,[d(C(D),{title:"Liste des Formations"}),d(A,null,{default:m(()=>[e("div",M,[e("div",N,[e("h1",B,[e("i",{class:i(["fas fa-book mr-3",r.base,"transition-colors duration-300 hover:"+r.hover])},null,2),t[1]||(t[1]=o(" Liste des Formations "))]),e("div",$,[e("div",q,[e("i",{class:i(["fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2",r.base])},null,2),F(e("input",{type:"text","onUpdate:modelValue":t[0]||(t[0]=s=>n.value=s),placeholder:"Rechercher...",class:"w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-sm sm:text-base"},null,512),[[H,n.value]])]),d(p,{href:a.route("formations.create"),class:"bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center"},{default:m(()=>t[2]||(t[2]=[e("i",{class:"fas fa-plus mr-2 transition-colors duration-300 hover:text-green-300"},null,-1),o(" Ajouter ")])),_:1},8,["href"])])]),e("div",z,[(c(!0),u(y,null,S(l.value,(s,_)=>(c(),u("div",{key:s.id,class:"bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-card-in",style:V({animationDelay:`${.1*_}s`})},[e("div",T,[e("div",E,[e("h2",{class:"text-lg font-semibold text-gray-900 dark:text-gray-100 truncate",title:s.titre},[e("i",{class:i(["fas fa-book mr-2",r.base])},null,2),o(" "+x(s.titre),1)],8,R),e("span",U," ID: "+x(s.id),1)]),e("p",{class:"text-sm text-gray-600 dark:text-gray-300 line-clamp-2",title:s.description},x(s.description),9,G),e("div",J,[d(p,{href:a.route("formations.edit",s.id),class:i(["flex items-center text-sm",r.edit,"hover:"+r.editHover,"transition-colors duration-300"])},{default:m(()=>t[3]||(t[3]=[e("i",{class:"fas fa-edit mr-1 animate-pulse"},null,-1),o(" Modifier ")])),_:2},1032,["href","class"]),e("button",{onClick:Q=>h(s.id),class:i(["flex items-center text-sm",r.delete,"hover:"+r.deleteHover,"transition-colors duration-300"])},t[4]||(t[4]=[e("i",{class:"fas fa-trash mr-1 animate-pulse"},null,-1),o(" Supprimer ")]),10,K),d(p,{href:a.route("formations.manageDisciplineAssociations",s.id),class:i(["flex items-center text-sm",r.manage,"hover:"+r.manageHover,"transition-colors duration-300"])},{default:m(()=>t[5]||(t[5]=[e("i",{class:"fas fa-cogs mr-1"},null,-1),o(" Disciplines ")])),_:2},1032,["href","class"])])])],4))),128)),l.value.length===0?(c(),u("div",O,t[6]||(t[6]=[e("i",{class:"fas fa-exclamation-circle mr-2 text-xl"},null,-1),o(" Aucune formation trouvée ")]))):L("",!0)])])]),_:1})],64))}}),se=I(P,[["__scopeId","data-v-c34f9553"]]);export{se as default};
