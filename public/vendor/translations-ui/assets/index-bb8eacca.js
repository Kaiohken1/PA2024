import{_ as $}from"./layout-dashboard.vue_vue_type_script_setup_true_lang-1d9d39f3.js";import{_ as L}from"./pagination.vue_vue_type_script_setup_true_lang-15830f26.js";import{u as I,_ as N}from"./use-confirmation-dialog-1471177b.js";import{_ as T}from"./base-button.vue_vue_type_script_setup_true_lang-3d6cef8e.js";import{_ as A}from"./icon-trash-62f8df5f.js";import{d as E,l as x,n as F,o as n,h as a,b as e,w as s,f as t,p as m,c as O,F as v,q as P,t as r,u as l,a as c,O as q,Z as R,i as S}from"./app-8d2ddf0a.js";import{_ as Y}from"./icon-plus-5ba4f8f8.js";import{_ as G}from"./invited-table.vue_vue_type_script_setup_true_lang-ffc54ae5.js";import{u as J}from"./use-auth-9835d026.js";import"./icon-publish-eaee4335.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./logo-47b5a18d.js";import"./transition-fb8d0e84.js";import"./icon-arrow-right-19101772.js";import"./icon-arrow-left-9aff8bd7.js";import"./dialog-6de4cc28.js";import"./invited-item.vue_vue_type_script_setup_true_lang-d72a345f.js";const K={class:"mx-auto max-w-7xl px-6 lg:px-8"},Q={class:"w-full divide-y overflow-hidden rounded-md bg-white shadow"},U={class:"w-full shadow-md"},W={class:"flex h-14 w-full divide-x"},X=t("div",{class:"grid w-full grid-cols-2 divide-x md:grid-cols-3"},[t("div",{class:"flex w-full items-center justify-start px-4"},[t("span",{class:"text-sm font-medium text-gray-400"},"Name")]),t("div",{class:"flex w-full items-center justify-start px-4"},[t("span",{class:"text-sm font-medium text-gray-400"},"Email")]),t("div",{class:"hidden w-full items-center justify-start px-4 md:flex"},[t("span",{class:"text-sm font-medium text-gray-400"},"Role")])],-1),tt={class:"grid w-16"},et={class:"flex h-14 w-full divide-x"},st={class:"grid w-full grid-cols-2 divide-x md:grid-cols-3"},ot={class:"flex w-full items-center justify-start px-4"},it={class:"truncate text-sm font-medium text-gray-500"},nt={class:"flex w-full items-center justify-start px-4"},lt={class:"truncate text-sm font-medium text-gray-500"},at={class:"hidden w-full items-center justify-start px-4 md:flex"},rt={class:"truncate whitespace-nowrap text-sm font-medium text-gray-500"},ct={class:"grid w-16"},dt={key:0,disabled:"",class:"group flex cursor-not-allowed items-center justify-center bg-gray-50 px-3"},mt={class:"flex flex-col p-6"},ut=t("span",{class:"text-xl font-medium text-gray-700"},"Are you sure?",-1),_t={class:"mt-2 text-sm text-gray-500"},ft={class:"font-medium"},pt={class:"mt-4 flex gap-4"},Lt=E({__name:"index",props:{invited:{},contributors:{}},setup(ht){const w=J().value,{loading:C,showDialog:y,openDialog:u,performAction:b,closeDialog:k}=I(),D=async o=>{await b(()=>q.delete(route("ltu.contributors.delete",o)))};return(o,_)=>{const j=R,V=Y,z=S,f=A,p=T,B=N,M=L,h=x("tab"),Z=x("tabs"),H=$,d=F("tooltip");return n(),a(v,null,[e(j,{title:"Contributors"}),e(H,null,{default:s(()=>[t("div",K,[e(Z,null,{default:s(()=>[e(h,{name:"Contributors",prefix:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.49984 9.99996C9.10817 9.99996 10.4165 8.69163 10.4165 7.08329C10.4165 5.47496 9.10817 4.16663 7.49984 4.16663C5.8915 4.16663 4.58317 5.47496 4.58317 7.08329C4.58317 8.69163 5.8915 9.99996 7.49984 9.99996ZM1.6665 14.375C1.6665 12.4333 5.54984 11.4583 7.49984 11.4583C9.44984 11.4583 13.3332 12.4333 13.3332 14.375V15.8333H1.6665V14.375ZM7.49984 13.125C6.00817 13.125 4.3165 13.6833 3.6165 14.1666H11.3832C10.6832 13.6833 8.9915 13.125 7.49984 13.125ZM8.74984 7.08329C8.74984 6.39163 8.1915 5.83329 7.49984 5.83329C6.80817 5.83329 6.24984 6.39163 6.24984 7.08329C6.24984 7.77496 6.80817 8.33329 7.49984 8.33329C8.1915 8.33329 8.74984 7.77496 8.74984 7.08329ZM13.3665 11.5083C14.3332 12.2083 14.9998 13.1416 14.9998 14.375V15.8333H18.3332V14.375C18.3332 12.6916 15.4165 11.7333 13.3665 11.5083ZM15.4165 7.08329C15.4165 8.69163 14.1082 9.99996 12.4998 9.99996C12.0498 9.99996 11.6332 9.89163 11.2498 9.70829C11.7748 8.96663 12.0832 8.05829 12.0832 7.08329C12.0832 6.10829 11.7748 5.19996 11.2498 4.45829C11.6332 4.27496 12.0498 4.16663 12.4998 4.16663C14.1082 4.16663 15.4165 5.47496 15.4165 7.08329Z" /></svg>'},{default:s(()=>[t("div",Q,[t("div",U,[t("div",W,[X,t("div",tt,[m((n(),O(z,{href:o.route("ltu.contributors.invite"),class:"group flex items-center justify-center px-3 hover:bg-blue-50"},{default:s(()=>[e(V,{class:"size-5 text-gray-400 group-hover:text-blue-600"})]),_:1},8,["href"])),[[d,"Invite Contributor"]])])])]),(n(!0),a(v,null,P(o.contributors.data,i=>(n(),a("div",{key:i.id,class:"w-full hover:bg-gray-100"},[t("div",et,[t("div",st,[t("div",ot,[t("span",it,r(i.name),1)]),t("div",nt,[t("span",lt,r(i.email),1)]),t("div",at,[t("div",rt,r(i.role.label),1)])]),t("div",ct,[l(w).id===i.id?m((n(),a("button",dt,[e(f,{class:"size-5 text-gray-400"})])),[[d,"You cannot delete yourself!"]]):m((n(),a("button",{key:1,class:"group flex items-center justify-center px-3 hover:bg-red-50",onClick:_[0]||(_[0]=(...g)=>l(u)&&l(u)(...g))},[e(f,{class:"size-5 text-gray-400 group-hover:text-red-600"})])),[[d,"Delete"]])]),e(B,{size:"sm",show:l(y)},{default:s(()=>[t("div",mt,[ut,t("span",_t,[c(" This action cannot be undone, This will permanently delete the "),t("span",ft,r(i.name),1),c(" invitation. ")]),t("div",pt,[e(p,{variant:"secondary",type:"button",size:"lg","full-width":"",onClick:l(k)},{default:s(()=>[c(" Cancel ")]),_:1},8,["onClick"]),e(p,{variant:"danger",type:"button",size:"lg","is-loading":l(C),"full-width":"",onClick:g=>D(i.id)},{default:s(()=>[c(" Delete ")]),_:2},1032,["is-loading","onClick"])])])]),_:2},1032,["show"])])]))),128)),e(M,{links:o.contributors.links,meta:o.contributors.meta},null,8,["links","meta"])])]),_:1}),e(h,{name:"Invited",prefix:'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><g><rect style="fill:none;" width="24" height="24"/><path d="M20,4H4C2.9,4,2,4.9,2,6l0,12c0,1.1,0.9,2,2,2h8v-2H4V8l8,5l8-5v5h2V6C22,4.9,21.1,4,20,4z M12,11L4,6h16L12,11z"/></g><polygon points="22,18 22,20 19.4,20 19.4,22.6 17.4,22.6 17.4,20 14.8,20 14.8,18 17.4,18 17.4,15.4 19.4,15.4 19.4,18 "/></svg>'},{default:s(()=>[e(G,{invitations:o.invited},null,8,["invitations"])]),_:1})]),_:1})])]),_:1})],64)}}});export{Lt as default};
